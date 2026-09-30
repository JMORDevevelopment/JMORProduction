<?php

use App\Models\Language;

beforeEach(function () {
    Language::query()->delete();
    Language::create(['name' => 'english', 'code' => 'en', 'sort_order' => 0]);
    Language::create(['name' => 'macedonian', 'code' => 'mk', 'sort_order' => 1]);
});

test('the header shows a language switcher for every language', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('/lang/change/english', false)
        ->assertSee('/lang/change/macedonian', false)
        ->assertSee('English')
        ->assertSee('Macedonian');
});

test('switching language stores the session and redirects home', function () {
    $this->get(route('lang.change', 'macedonian'))
        ->assertRedirect(route('home'))
        ->assertSessionHas('lang', 'macedonian');
});

test('strings are translated after switching to macedonian', function () {
    $this->get(route('lang.change', 'macedonian'));

    $this->get(route('sign-up'))
        ->assertOk()
        ->assertSee('Име');

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Најава');
});

test('the site defaults to english', function () {
    $this->get(route('sign-up'))
        ->assertOk()
        ->assertSee('First Name');
});

test('the lang cookie seeds the session language', function () {
    $this->withUnencryptedCookie('lang', 'macedonian');

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Најава')
        ->assertSessionHas('lang', 'macedonian');
});

test('unknown language names are ignored', function () {
    $this->get(route('lang.change', 'klingon'))
        ->assertRedirect(route('home'));

    expect(session('lang'))->toBeNull();
});

test('sign-up validation messages are translated in macedonian', function () {
    $this->get(route('lang.change', 'macedonian'));

    $this->post(route('sign-up.validate'), ['firstname' => ''])
        ->assertSessionHasErrors('firstname');

    expect(session('errors')->first('firstname'))->toBe('Внесете име');
});
