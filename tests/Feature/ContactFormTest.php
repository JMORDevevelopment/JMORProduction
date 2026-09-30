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
        ->assertSee('<p class="mb-0" style="cursor:pointer;" onclick="inquiryformtwo()">Apply Now</p>', false);
});

test('contact page wires the inquiry and chat tiles to the inquiry form', function () {
    $this->get(route('contact'))
        ->assertSuccessful()
        ->assertSee('<p class="mb-0" style="cursor:pointer;" onclick="inquiryform()">Chat Now</p>', false)
        ->assertSee('<p class="mb-0" style="cursor:pointer;" onclick="inquiryform()">Inquire Now</p>', false);
});

test('contact page wires the address and phone tiles to the reveal handlers', function () {
    $this->get(route('contact'))
        ->assertSuccessful()
        ->assertSee("reveal('.viewadres', 'viewadres')", false)
        ->assertSee("reveal('.viewnmbr', 'viewnmbr')", false);
});

test('contact page renders the address line break instead of raw markup', function () {
    DB::table('settings')->insert([
        ['option' => 'address', 'value' => '799 Franklin Ave Unit 3<br>Franklin Lakes NJ 07417'],
    ]);

    $this->get(route('contact'))
        ->assertSuccessful()
        ->assertSee('799 Franklin Ave Unit 3<br />', false)
        ->assertDontSee('&lt;br&gt;', false);
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
