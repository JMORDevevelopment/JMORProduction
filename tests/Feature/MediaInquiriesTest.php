<?php

use App\Models\Language;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);

    // Seed the settings table so Setting::get('email') doesn't fail
    DB::table('settings')->insert([
        ['option' => 'email', 'value' => 'admin@test.com'],
    ]);
});

it('displays the media inquiries form page', function () {
    $response = $this->get(route('media-inquiries'));

    $response->assertStatus(200);
    $response->assertSee('Media inquiries');
    $response->assertSee('Drag To Verify');
    $response->assertSee('media-inquiries');
});

it('validates required fields on media inquiry submission', function () {
    $response = $this->post(route('media-inquiries.validate'), []);

    $response->assertSessionHasErrors([
        'media',
        'contact',
        'email',
        'phone',
        'story_concept',
        'press_deadline',
        'story_details',
        'best_contact',
        'protection_question',
    ]);
});

it('validates captcha answer is correct', function () {
    $this->get(route('media-inquiries'));

    $response = $this->post(route('media-inquiries.validate'), [
        'media' => 'CNN',
        'contact' => 'John Doe',
        'email' => 'john@cnn.com',
        'phone' => '555-1234',
        'story_concept' => 'Tech innovation',
        'press_deadline' => '2026-08-30',
        'story_details' => 'A story about tech.',
        'best_contact' => 'Monday 9am',
        'firstNumber' => 5,
        'secondNumber' => 3,
        'protection_question' => 99,
    ]);

    $response->assertSessionHasErrors('protection_question');
});

it('rejects a captcha answer when the challenge was never rendered', function () {
    $response = $this->post(route('media-inquiries.validate'), [
        'media' => 'CNN',
        'contact' => 'John Doe',
        'email' => 'john@cnn.com',
        'phone' => '555-1234',
        'story_concept' => 'Tech innovation',
        'press_deadline' => '2026-08-30',
        'story_details' => 'A story about tech.',
        'best_contact' => 'Monday 9am',
        'firstNumber' => 5,
        'secondNumber' => 3,
        'protection_question' => 8,
    ]);

    $response->assertSessionHasErrors('protection_question');
});

it('creates a media inquiry with valid data', function () {
    $this->get(route('media-inquiries'));
    $captcha = session('captcha_numbers');

    $response = $this->post(route('media-inquiries.validate'), [
        'media' => 'CNN',
        'contact' => 'John Doe',
        'email' => 'john@cnn.com',
        'phone' => '555-1234',
        'story_concept' => 'Tech innovation',
        'press_deadline' => '2026-08-30',
        'story_details' => 'A story about tech.',
        'best_contact' => 'Monday 9am',
        'firstNumber' => 5,
        'secondNumber' => 3,
        'protection_question' => $captcha[0] + $captcha[1],
    ]);

    $response->assertRedirect(route('media-inquiries'));

    $this->assertDatabaseHas('media_inquiries', [
        'media' => 'CNN',
        'contact' => 'John Doe',
        'email' => 'john@cnn.com',
        'phone' => '555-1234',
        'story_concept' => 'Tech innovation',
        'press_deadline' => '2026-08-30',
        'story_details' => 'A story about tech.',
        'best_contact' => 'Monday 9am',
        'media_status' => 0,
    ]);
});

it('preserves old input on validation failure', function () {
    $response = $this->post(route('media-inquiries.validate'), [
        'media' => '',
        'contact' => '',
    ]);

    $response->assertSessionHasErrors();
});

it('translates the email error message in macedonian', function () {
    Language::query()->delete();
    Language::create(['name' => 'english', 'code' => 'en', 'sort_order' => 0]);
    Language::create(['name' => 'macedonian', 'code' => 'mk', 'sort_order' => 1]);

    $this->get(route('lang.change', 'macedonian'));

    $this->post(route('media-inquiries.validate'), ['email' => ''])
        ->assertSessionHasErrors('email');

    expect(session('errors')->first('email'))->toBe('Внесете емаил');
});
