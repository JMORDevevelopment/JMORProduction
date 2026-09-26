<?php

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);
});

test('every response carries the baseline security headers', function () {
    $response = $this->get('/');

    $response->assertSuccessful();
    $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
});

test('contact submissions are rate limited after five attempts', function () {
    $payload = [
        'name' => 'Throttle Tester',
        'email' => 'throttle@example.com',
        'phone' => '555-0000',
        'reason' => 'General Inquiry',
        'message' => 'Hello there',
        'firstNumber' => 1,
        'secondNumber' => 1,
        'protection_question' => 2,
    ];

    for ($attempt = 0; $attempt < 5; $attempt++) {
        $this->post(route('contact.submit'), $payload);
    }

    $this->post(route('contact.submit'), $payload)->assertStatus(429);
});
