<?php

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Schema;

/**
 * The legacy migration set cannot run on the in-memory sqlite test database
 * (duplicate table definitions and incompatible foreign keys), so only the
 * tables these tests touch are created manually.
 */
beforeEach(function () {
    Schema::dropIfExists('testimony_form');

    Schema::create('testimony_form', function ($table) {
        $table->integer('id', true);
        $table->integer('customer_id');
        $table->text('service_used');
        $table->text('message');
        $table->integer('status')->default(0);
        $table->timestamp('published')->nullable();
    });

    $this->user = new User([
        'firstname' => 'Jane',
        'lastname' => 'Doe',
        'email' => 'jane@example.com',
    ]);

    // user_id is not mass-assignable (guarded), so set it directly.
    $this->user->user_id = 9826;

    $this->actingAs($this->user);

    // Feature tests run through the web middleware group; bypass CSRF only.
    $this->withoutMiddleware(PreventRequestForgery::class);
});

afterEach(function () {
    Schema::dropIfExists('testimony_form');
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
