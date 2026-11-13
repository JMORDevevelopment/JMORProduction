<?php

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;

beforeEach(function () {
    // Feature tests run through the web middleware group; bypass CSRF only.
    $this->withoutMiddleware(PreventRequestForgery::class);

    $this->user = new User([
        'firstname' => 'Jane',
        'lastname' => 'Doe',
        'email' => 'jane@example.com',
    ]);

    // user_id is not mass-assignable (guarded), so set it directly.
    $this->user->user_id = 9826;

    $this->actingAs($this->user);
});

test('a logged-in user can submit a new testimonial without an id', function () {
    $this->post(route('dashboard.testimonial_validate'), [
        'service_used' => 'Managed IT Services',
        'message' => 'Great support team!',
    ])->assertRedirect(route('dashboard.testimonial'));

    $this->assertDatabaseHas('testimony_form', [
        'customer_id' => 9826,
        'service_used' => 'Managed IT Services',
        'message' => 'Great support team!',
        'status' => 0,
    ]);
});

test('a logged-in user can update their own testimonial by id', function () {
    $testimonial = Testimonial::create([
        'customer_id' => 9826,
        'service_used' => 'Old service',
        'message' => 'Old message',
        'status' => 0,
    ]);

    $this->post(route('dashboard.testimonial_validate', $testimonial->id), [
        'service_used' => 'New service',
        'message' => 'New message',
    ])->assertRedirect(route('dashboard.testimonial'));

    $this->assertDatabaseHas('testimony_form', [
        'id' => $testimonial->id,
        'customer_id' => 9826,
        'service_used' => 'New service',
        'message' => 'New message',
    ]);
});

test('a user cannot update another customer\'s testimonial', function () {
    $testimonial = Testimonial::create([
        'customer_id' => 1,
        'service_used' => 'Someone else',
        'message' => 'Not mine',
        'status' => 0,
    ]);

    $this->post(route('dashboard.testimonial_validate', $testimonial->id), [
        'service_used' => 'Hijacked',
        'message' => 'Hijacked',
    ])->assertNotFound();

    $this->assertDatabaseHas('testimony_form', [
        'id' => $testimonial->id,
        'service_used' => 'Someone else',
    ]);
});

test('testimonial validation rejects empty service and message', function () {
    $this->post(route('dashboard.testimonial_validate'), [
        'service_used' => '',
        'message' => '',
    ])->assertSessionHasErrors(['service_used', 'message']);
});

test('consent records only a privacy-safe public attribution', function () {
    $this->post(route('dashboard.testimonial_validate'), [
        'service_used' => 'Managed IT Services',
        'message' => 'Happy to recommend JMOR.',
        'consent_to_publish' => '1',
    ])->assertRedirect(route('dashboard.testimonial'));

    $this->assertDatabaseHas('testimony_form', [
        'customer_id' => 9826,
        'display_name' => 'Jane D.',
        'consent_to_publish' => 1,
        'status' => 0,
    ]);
});

test('withdrawing consent clears public attribution and removes the testimonial from view', function () {
    $testimonial = Testimonial::create([
        'customer_id' => 9826,
        'display_name' => 'Jane D.',
        'service_used' => 'Managed IT Services',
        'message' => 'Previously approved copy.',
        'status' => 1,
        'consent_to_publish' => true,
        'verified_at' => now(),
    ]);

    $this->post(route('dashboard.testimonial_validate', $testimonial->id), [
        'service_used' => 'Managed IT Services',
        'message' => 'Previously approved copy.',
    ])->assertRedirect(route('dashboard.testimonial'));

    $this->assertDatabaseHas('testimony_form', [
        'id' => $testimonial->id,
        'display_name' => null,
        'consent_to_publish' => 0,
        'verified_at' => null,
        'status' => 0,
    ]);
});

test('editing approved testimonial copy requires verification again', function () {
    $testimonial = Testimonial::create([
        'customer_id' => 9826,
        'display_name' => 'Jane D.',
        'service_used' => 'Managed IT Services',
        'message' => 'Original approved copy.',
        'status' => 1,
        'consent_to_publish' => true,
        'verified_at' => now(),
    ]);

    $this->post(route('dashboard.testimonial_validate', $testimonial->id), [
        'service_used' => 'Managed IT Services',
        'message' => 'Edited customer copy.',
        'consent_to_publish' => '1',
    ])->assertRedirect(route('dashboard.testimonial'));

    $this->assertDatabaseHas('testimony_form', [
        'id' => $testimonial->id,
        'message' => 'Edited customer copy.',
        'consent_to_publish' => 1,
        'verified_at' => null,
        'status' => 0,
    ]);
});
