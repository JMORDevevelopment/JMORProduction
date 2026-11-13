<?php

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);

    DB::table('settings')->insert([
        ['option' => 'email', 'value' => 'admin@test.com'],
    ]);
});

test('contact page seeds the captcha challenge in session', function () {
    $this->get(route('contact'))->assertSuccessful();

    $captcha = session('captcha_numbers');
    expect($captcha)->toBeArray();
    expect($captcha)->toHaveCount(2);
});

test('contact form rejects a wrong captcha answer', function () {
    $this->get(route('contact'));

    $response = $this->post(route('contact.submit'), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '555-1234',
        'reason' => 'General Inquiry',
        'message' => 'Hello there',
        'firstNumber' => 5,
        'secondNumber' => 3,
        'protection_question' => 99,
    ]);

    $response->assertSessionHasErrors('protection_question');
    $this->assertDatabaseMissing('contact_us', ['email' => 'jane@example.com']);
});

test('contact form rejects a captcha without a rendered challenge', function () {
    $response = $this->post(route('contact.submit'), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '555-1234',
        'reason' => 'General Inquiry',
        'message' => 'Hello there',
        'firstNumber' => 5,
        'secondNumber' => 3,
        'protection_question' => 8,
    ]);

    $response->assertSessionHasErrors('protection_question');
});

test('contact form creates a record with the session captcha answer', function () {
    $this->get(route('contact'));
    $captcha = session('captcha_numbers');

    $response = $this->post(route('contact.submit'), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '555-1234',
        'reason' => 'General Inquiry',
        'message' => 'Hello there',
        'firstNumber' => 5,
        'secondNumber' => 3,
        'protection_question' => $captcha[0] + $captcha[1],
    ]);

    $response->assertRedirect(route('contact'));
    $this->assertDatabaseHas('contact_us', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);
});

test('request information form creates a record with the session captcha answer', function () {
    $this->get(route('request-information'));
    $captcha = session('captcha_numbers');

    $response = $this->post(route('request-information.validate'), [
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'company' => 'Acme',
        'email' => 'jane@example.com',
        'phone' => '555-1234',
        'service_intersted' => 'Managed IT',
        'message' => 'Please send details',
        'firstNumber' => 5,
        'secondNumber' => 3,
        'protection_question' => $captcha[0] + $captcha[1],
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('request_information', [
        'first_name' => 'Jane',
        'email' => 'jane@example.com',
    ]);
});

test('contact page wires the talk show tile to the interview application form', function () {
    $this->get(route('contact'))
        ->assertSuccessful()
        ->assertSee('onclick="inquiryformtwo()"', false)
        ->assertSee('Talk Show Guest', false);
});

test('contact page gives every acquisition pathway its own destination', function () {
    $this->get(route('contact'))
        ->assertSuccessful()
        // Technology assessment has its own page instead of reusing the form.
        ->assertSee('href="'.route('request-information').'"', false)
        // Web inquiry opens the inquiry form.
        ->assertSee('onclick="inquiryform()"', false)
        // Live support no longer competes by reusing the inquiry form.
        ->assertSee('data-conversion="live_support"', false);
});

test('contact page shows the address and phone instead of hiding them behind toggles', function () {
    DB::table('settings')->insert([
        ['option' => 'phone', 'value' => '(877) 767-5667'],
    ]);

    $this->get(route('contact'))
        ->assertSuccessful()
        ->assertSee('href="tel:8777675667"', false)
        ->assertSee('Get directions', false);
});

test('contact page renders the address line break instead of raw markup', function () {
    DB::table('settings')->insert([
        ['option' => 'address', 'value' => '799 Franklin Avenue, Suite 3<br>Franklin Lakes, NJ 07417'],
    ]);

    $this->get(route('contact'))
        ->assertSuccessful()
        ->assertSee('799 Franklin Avenue, Suite 3<br />', false)
        ->assertSee('Franklin Lakes, NJ 07417', false)
        ->assertDontSee('&lt;br&gt;', false);
});

test('contact page publishes the verified address in the map and directions link', function () {
    DB::table('settings')->insert([
        ['option' => 'address', 'value' => '799 Franklin Avenue, Suite 3<br>Franklin Lakes, NJ 07417'],
    ]);

    $encoded = urlencode('799 Franklin Avenue, Suite 3, Franklin Lakes, NJ 07417');

    $this->get(route('contact'))
        ->assertSuccessful()
        ->assertSee('https://www.google.com/maps?q='.$encoded.'&output=embed', false)
        ->assertSee('https://maps.google.com/?q='.$encoded, false)
        ->assertSee('title="Map of 799 Franklin Avenue, Suite 3, Franklin Lakes, NJ 07417"', false)
        ->assertDontSee('Franklin%20Ave%20%233', false)
        ->assertSee('loading="lazy"', false);
});

test('the address migration replaces the unverified formatting and keeps editor corrections', function () {
    DB::table('settings')->insert([
        ['option' => 'address', 'value' => '799 Franklin Ave  Unit 3<br> Franklin Lakes NJ 07417'],
        ['option' => 'phone', 'value' => '(877) 767-5667a'],
        ['option' => 'meta_title', 'value' => 'JMOR'],
    ]);

    DB::table('settings')->insert([
        ['option' => 'address', 'value' => '799 Franklin Avenue, Suite 300<br>Franklin Lakes, NJ 07417'],
    ]);

    $migration = include database_path('migrations/2026_11_13_173000_publish_verified_office_address.php');
    $migration->up();

    $addresses = DB::table('settings')->where('option', 'address')->orderBy('id')->pluck('value')->all();

    expect($addresses)->toBe([
        '799 Franklin Avenue, Suite 3<br>Franklin Lakes, NJ 07417',
        '799 Franklin Avenue, Suite 300<br>Franklin Lakes, NJ 07417',
    ])->and(DB::table('settings')->where('option', 'phone')->value('value'))
        ->toBe('(877) 767-5667');
});

test('contact page renders the talk show interview form', function () {
    $this->get(route('contact'))
        ->assertSuccessful()
        ->assertSee('id="form_two"', false)
        ->assertSee('action="'.route('contact.interview').'"', false)
        ->assertSee('Interview Pitch Concept', false)
        ->assertSee('id="captchab"', false)
        ->assertSee('id="add_work"', false);
});

function interviewPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Jane',
        'last_name' => 'Doe',
        'company_name' => 'Acme',
        'phone' => '555-1234',
        'email' => 'jane@example.com',
        'bio' => 'Long time radio producer',
        'work_name' => ['The Tonight Show'],
        'work_detail' => ['Guest appearance'],
        'weblink' => ['https://example.com/show'],
        'interview' => 'I want to talk about edge computing',
        'counter' => '1',
        'firstNumber' => 5,
        'secondNumber' => 3,
    ], $overrides);
}

test('talk show interview application creates a record with the session captcha answer', function () {
    $this->get(route('contact'));
    $captcha = session('captcha_numbers');

    $response = $this->post(route('contact.interview'), interviewPayload([
        'protection_question' => $captcha[0] + $captcha[1],
    ]));

    $response->assertRedirect(route('contact'));
    $response->assertSessionHas('contact_success');
    $this->assertDatabaseHas('talk_show', [
        'name' => 'Jane',
        'last_name' => 'Doe',
        'email' => 'jane@example.com',
        'work_name' => 'The Tonight Show',
        'status' => 0,
    ]);
});

test('talk show interview application rejects a wrong captcha answer', function () {
    $this->get(route('contact'));

    $response = $this->post(route('contact.interview'), interviewPayload([
        'protection_question' => 99,
    ]));

    $response->assertSessionHasErrors('protection_question');
    $this->assertDatabaseMissing('talk_show', ['email' => 'jane@example.com']);
});

test('talk show interview application validates its required fields', function () {
    $this->get(route('contact'));
    $captcha = session('captcha_numbers');

    $response = $this->post(route('contact.interview'), [
        'protection_question' => $captcha[0] + $captcha[1],
        'firstNumber' => 5,
        'secondNumber' => 3,
    ]);

    $response->assertSessionHasErrors([
        'name', 'last_name', 'phone', 'email', 'bio', 'work_name', 'interview',
    ]);
    $this->assertDatabaseMissing('talk_show', ['email' => 'jane@example.com']);
});

test('a failed interview submission reopens the interview form with its errors', function () {
    $this->get(route('contact'));

    // Note: assertSessionHasErrors() ages the flash data in this Laravel
    // version, so the follow-up GET would lose the errors — assert the
    // rendered page directly instead.
    $this->post(route('contact.interview'), interviewPayload([
        'protection_question' => 99,
    ]));

    $this->get(route('contact'))
        ->assertSuccessful()
        ->assertSee('inquiryformtwo();', false)
        ->assertSee('Your answer is wrong!', false);
});

test('the add work endpoint returns an html block with escaped values', function () {
    $response = $this->post(route('contact.load_restaurants'), [
        'id' => 2,
        'work_name' => '<b>My Show</b>',
        'work_detail' => 'A detail',
        'work_web' => 'https://example.com',
    ]);

    $payload = $response->json();

    expect($payload['msg'] ?? null)->toBeNull();
    expect($payload['html'])->toContain('work_name[]');
    expect($payload['html'])->toContain('&lt;b&gt;My Show&lt;/b&gt;');
    expect($payload['html'])->not->toContain('<b>My Show</b>');
});
