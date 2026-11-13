<?php

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Support\Facades\DB;

function testimonialPageFixture(array $attributes = []): Testimonial
{
    return Testimonial::create(array_merge([
        'customer_id' => null,
        'display_name' => 'Test customer',
        'service_used' => 'Managed IT',
        'message' => 'A verified testimonial.',
        'status' => 1,
        'consent_to_publish' => true,
        'verified_at' => now(),
        'published' => now(),
    ], $attributes));
}

test('the page shows only approved, verified testimonials with publication consent', function () {
    testimonialPageFixture([
        'display_name' => 'Visible Customer',
        'message' => 'This testimonial is approved and verified.',
    ]);

    testimonialPageFixture([
        'display_name' => 'Unverified Customer',
        'message' => 'This testimonial has not been verified.',
        'verified_at' => null,
    ]);

    testimonialPageFixture([
        'display_name' => 'No Consent Customer',
        'message' => 'This testimonial has no publication consent.',
        'consent_to_publish' => false,
    ]);

    testimonialPageFixture([
        'display_name' => 'Pending Customer',
        'message' => 'This testimonial is still pending.',
        'status' => 0,
    ]);

    $this->get(route('testimonials'))
        ->assertSuccessful()
        ->assertSee('Visible Customer')
        ->assertSee('This testimonial is approved and verified.')
        ->assertDontSee('Unverified Customer')
        ->assertDontSee('No Consent Customer')
        ->assertDontSee('Pending Customer');
});

test('the page never exposes a customer full name or email address', function () {
    $user = User::create([
        'firstname' => 'Jane',
        'lastname' => 'Doe',
        'email' => 'jane.doe@example.com',
        'password' => bcrypt('password'),
        'date_added' => now(),
    ]);

    testimonialPageFixture([
        'customer_id' => $user->user_id,
        'display_name' => 'Jane D.',
        'message' => 'Privacy-safe attribution quote.',
    ]);

    $this->get(route('testimonials'))
        ->assertSuccessful()
        ->assertSee('Jane D.')
        ->assertSee('Privacy-safe attribution quote.')
        ->assertDontSee('Jane Doe')
        ->assertDontSee('jane.doe@example.com');
});

test('the approved published testimonials are restored', function () {
    $this->get(route('testimonials'))
        ->assertSuccessful()
        ->assertSee('John D., Mahwah resident')
        ->assertSee('Sarah K., business owner')
        ->assertSee('Mark Z., Mahwah resident')
        ->assertSee('Their team is knowledgeable, friendly, and always responsive to our needs.');
});

test('the testimonial seed migration does not create duplicates', function () {
    $migration = include database_path('migrations/2026_11_13_142717_restore_verified_customer_testimonials.php');
    $migration->up();

    expect(DB::table('testimony_form')
        ->where('source_url', 'https://jmor.com/blog/mahwah-nj-it-services-computer-support-for-home-business')
        ->count())->toBe(3);
});
