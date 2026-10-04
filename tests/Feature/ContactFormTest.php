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
