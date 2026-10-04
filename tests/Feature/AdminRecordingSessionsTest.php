<?php

use App\Filament\Admin\Resources\RecordingSessionResource\Pages\CreateRecordingSession;
use App\Filament\Admin\Resources\RecordingSessionResource\Pages\EditRecordingSession;
use App\Filament\Admin\Resources\RecordingSessionResource\Pages\ListRecordingSessions;
use App\Models\Admin;
use App\Models\RecordingSession;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Livewire\Livewire;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);

    $this->admin = Admin::create([
        'firstname' => 'Sessions',
        'lastname' => 'Admin',
        'email' => 'sessions.admin@test.com',
        'password' => bcrypt('password'),
        'status' => 1,
        'role' => 1,
        'image' => '',
        'last_login' => now(),
        'date_register' => now(),
    ]);

    $this->admin->forceFill([
        'two_factor_secret' => encrypt('test-two-factor-secret'),
        'two_factor_confirmed_at' => now(),
    ])->save();
    $this->admin->setTwoFactorChallengePassed();

    $this->actingAs($this->admin, 'admin');

    $this->session = RecordingSession::create([
        'date_time' => '2026-10-15',
        'name' => 'Weekly Recording',
        'description' => 'Studio session',
        'user_id' => 0,
        'link' => 'https://example.com/recording',
    ]);
});

// ─── List ───────────────────────────────────────────────────────────────

test('admin can access recording sessions list page', function () {
    $this->get('/admin/recording-sessions')
        ->assertSuccessful()
        ->assertSee('Weekly Recording');
});

// ─── Create ─────────────────────────────────────────────────────────────

test('admin can create a recording session', function () {
    Livewire::test(CreateRecordingSession::class)
        ->set('data.name', 'New Session')
        ->set('data.description', 'Talk show recording')
        ->set('data.date_time', '2026-11-01')
        ->set('data.link', 'https://example.com/new')
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('events_calendar', [
        'name' => 'New Session',
        'date_time' => '2026-11-01',
        'link' => 'https://example.com/new',
    ]);
});

test('recording session requires name, description and date', function () {
    Livewire::test(CreateRecordingSession::class)
        ->set('data.name', '')
        ->set('data.description', '')
        ->set('data.date_time', null)
        ->call('create')
        ->assertHasFormErrors(['name', 'description', 'date_time']);
});

// ─── Edit ───────────────────────────────────────────────────────────────

test('admin can edit a recording session', function () {
    Livewire::test(EditRecordingSession::class, ['record' => $this->session->id])
        ->set('data.name', 'Rescheduled Session')
        ->set('data.date_time', '2026-10-20')
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('events_calendar', [
        'id' => $this->session->id,
        'name' => 'Rescheduled Session',
        'date_time' => '2026-10-20',
    ]);
});

// ─── Delete ─────────────────────────────────────────────────────────────

test('admin can delete a recording session', function () {
    Livewire::test(ListRecordingSessions::class)
        ->callTableAction('delete', $this->session);

    $this->assertDatabaseMissing('events_calendar', ['id' => $this->session->id]);
});

// ─── Unauthenticated ────────────────────────────────────────────────────

test('unauthenticated user cannot access recording sessions', function () {
    auth()->guard('admin')->logout();

    $this->get('/admin/recording-sessions')->assertRedirect();
});
