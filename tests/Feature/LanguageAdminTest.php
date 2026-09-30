<?php

use App\Filament\Admin\Resources\LanguageResource\Pages\CreateLanguage;
use App\Filament\Admin\Resources\LanguageResource\Pages\EditLanguage;
use App\Filament\Admin\Resources\LanguageResource\Pages\ListLanguages;
use App\Models\Admin;
use App\Models\Language;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Livewire\Livewire;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);

    $this->admin = Admin::create([
        'firstname' => 'Test',
        'lastname' => 'Admin',
        'email' => 'admin@test.com',
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

    Language::query()->delete();
    $this->english = Language::create(['name' => 'english', 'code' => 'en', 'sort_order' => 0]);
    $this->macedonian = Language::create(['name' => 'macedonian', 'code' => 'mk', 'sort_order' => 1]);
});

test('lists languages', function () {
    Livewire::test(ListLanguages::class)
        ->assertCanSeeTableRecords([$this->english, $this->macedonian])
        ->assertSee('english')
        ->assertSee('macedonian');
});

test('creates a language', function () {
    Livewire::test(CreateLanguage::class)
        ->fillForm(['name' => 'french', 'code' => 'fr'])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('language', ['name' => 'french', 'code' => 'fr']);
});

test('name and code are required', function () {
    Livewire::test(CreateLanguage::class)
        ->fillForm(['name' => '', 'code' => ''])
        ->call('create')
        ->assertHasErrors(['data.name', 'data.code']);
});

test('edits a language', function () {
    Livewire::test(EditLanguage::class, ['record' => $this->macedonian->getKey()])
        ->fillForm(['name' => 'macedonian', 'code' => 'mkd'])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('language', ['language_id' => $this->macedonian->getKey(), 'code' => 'mkd']);
});

test('deletes a language', function () {
    Livewire::test(ListLanguages::class)
        ->callTableAction('delete', $this->macedonian)
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseMissing('language', ['language_id' => $this->macedonian->getKey()]);
});
