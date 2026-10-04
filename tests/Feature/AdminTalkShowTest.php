<?php

use App\Filament\Admin\Resources\MarketingServiceResource\Pages\CreateMarketingService;
use App\Filament\Admin\Resources\TalkShowGuestResource\Pages\EditTalkShowGuest;
use App\Filament\Admin\Resources\TalkShowGuestResource\Pages\ListTalkShowGuests;
use App\Filament\Admin\Resources\TalkShowSettingResource\Pages\EditTalkShowSetting;
use App\Filament\Admin\Resources\TalkShowTemplateResource\Pages\CreateTalkShowTemplate;
use App\Mail\TalkShowAcceptance;
use App\Mail\TalkShowRevision;
use App\Models\Admin;
use App\Models\MarketingService;
use App\Models\TalkShow;
use App\Models\TalkShowSetting;
use App\Models\TalkShowTemplate;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);

    $this->admin = Admin::create([
        'firstname' => 'Talk',
        'lastname' => 'Show',
        'email' => 'talkshow.admin@test.com',
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

    $this->applicant = TalkShow::create([
        'name' => 'Ada',
        'last_name' => 'Lovelace',
        'company_name' => 'Analytical Co',
        'phone' => '555-0100',
        'email' => 'ada@example.com',
        'bio' => 'Original bio',
        'work_name' => 'Engineer',
        'work_detail' => 'Computing',
        'weblink' => 'https://example.com',
        'interview' => 'Original pitch',
        'ip' => '127.0.0.1',
        'date_time' => '2026-10-01 10:00:00',
        'status' => 0,
    ]);
});

// ─── Guest List ─────────────────────────────────────────────────────────

test('admin can access talk show guest list page', function () {
    $this->get('/admin/talk-show-guests')->assertSuccessful();
});

test('guest list shows applicants with status', function () {
    Livewire::test(ListTalkShowGuests::class)
        ->assertCanRenderTableColumn('email')
        ->assertCanRenderTableColumn('status')
        ->assertSee('ada@example.com')
        ->assertSee('Pending');
});

test('accepted applicants show an accepted badge', function () {
    $this->applicant->update(['status' => 1]);

    Livewire::test(ListTalkShowGuests::class)
        ->assertSee('Accepted');
});

// ─── Guest Edit ─────────────────────────────────────────────────────────

test('admin can edit a guest applicant', function () {
    Livewire::test(EditTalkShowGuest::class, ['record' => $this->applicant->id])
        ->set('data.name', 'Ada L.')
        ->set('data.bio', 'Updated bio')
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('talk_show', [
        'id' => $this->applicant->id,
        'name' => 'Ada L.',
        'bio' => 'Updated bio',
    ]);
});

// ─── Accept / Reopen ────────────────────────────────────────────────────

test('accept marks the applicant accepted and emails them', function () {
    Mail::fake();

    Livewire::test(ListTalkShowGuests::class)
        ->callTableAction('accept', $this->applicant);

    $this->assertDatabaseHas('talk_show', ['id' => $this->applicant->id, 'status' => 1]);

    Mail::assertSent(TalkShowAcceptance::class, function (TalkShowAcceptance $mail) {
        return $mail->hasTo('ada@example.com')
            && str_contains($mail->render(), 'Click here to order');
    });
});

test('accept is hidden once the applicant is accepted', function () {
    $this->applicant->update(['status' => 1]);

    Livewire::test(ListTalkShowGuests::class)
        ->assertTableActionHidden('accept', $this->applicant)
        ->assertTableActionVisible('reopen', $this->applicant);
});

test('reopen marks an accepted applicant pending again', function () {
    $this->applicant->update(['status' => 1]);
    Mail::fake();

    Livewire::test(ListTalkShowGuests::class)
        ->callTableAction('reopen', $this->applicant);

    $this->assertDatabaseHas('talk_show', ['id' => $this->applicant->id, 'status' => 0]);

    Mail::assertNothingSent();
});

// ─── Send Revision ──────────────────────────────────────────────────────

test('send revision emails the message to the applicant', function () {
    $template = TalkShowTemplate::create([
        'name' => 'Welcome',
        'content' => 'Welcome to the talk show.',
    ]);

    Mail::fake();

    Livewire::test(ListTalkShowGuests::class)
        ->callTableAction('revision', $this->applicant, [
            'template_id' => $template->id,
            'content' => 'Welcome to the talk show.',
        ]);

    Mail::assertSent(TalkShowRevision::class, function (TalkShowRevision $mail) use ($template) {
        return $mail->hasTo('ada@example.com')
            && str_contains($mail->render(), $template->content);
    });
});

// ─── Templates ──────────────────────────────────────────────────────────

test('admin can create a talk show template', function () {
    Livewire::test(CreateTalkShowTemplate::class)
        ->set('data.name', 'Follow up')
        ->set('data.content', 'Please send us your headshot.')
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('templates', [
        'name' => 'Follow up',
        'content' => 'Please send us your headshot.',
    ]);
});

// ─── Talk Show Settings ─────────────────────────────────────────────────

test('admin can update talk show settings', function () {
    $setting = TalkShowSetting::create([
        'price' => '450.00',
        'question' => 'Old question?',
    ]);

    Livewire::test(EditTalkShowSetting::class, ['record' => $setting->id])
        ->set('data.price', '500.00')
        ->set('data.question', 'New question?')
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('talk_show_settings', [
        'id' => $setting->id,
        'price' => '500.00',
        'question' => 'New question?',
    ]);
});

// ─── Marketing Services ─────────────────────────────────────────────────

test('admin can create a marketing service', function () {
    Livewire::test(CreateMarketingService::class)
        ->set('data.product_code', '100909')
        ->set('data.name', 'TLT')
        ->set('data.description', 'TLT Services')
        ->set('data.question', 'Why TLT?')
        ->set('data.price', '1.00')
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('services', [
        'product_code' => '100909',
        'name' => 'TLT',
        'price' => '1.00',
    ]);
});

test('marketing service list shows existing services', function () {
    MarketingService::create([
        'name' => 'Existing',
        'question' => 'Q?',
        'product_code' => '555',
        'description' => 'Desc',
        'price' => '9.99',
    ]);

    $this->get('/admin/marketing-services')
        ->assertSuccessful()
        ->assertSee('Existing');
});

// ─── Unauthenticated ────────────────────────────────────────────────────

test('unauthenticated user cannot access talk show guests', function () {
    auth()->guard('admin')->logout();

    $this->get('/admin/talk-show-guests')->assertRedirect();
});

// ─── Legacy 410s ────────────────────────────────────────────────────────

test('legacy product and category urls return 410', function () {
    $this->get('/product/edit/12')->assertStatus(410);
    $this->get('/cate/5')->assertStatus(410);
});
