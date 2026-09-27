<?php

use App\Filament\Admin\Resources\ContactUsResource\Pages\EditContactUs;
use App\Filament\Admin\Resources\ContactUsResource\Pages\ListContactUs;
use App\Filament\Admin\Resources\MediaInquiryResource\Pages\EditMediaInquiry;
use App\Filament\Admin\Resources\MediaInquiryResource\Pages\ListMediaInquiries;
use App\Filament\Admin\Resources\RequestInformationResource\Pages\ListRequestInformations;
use App\Filament\Admin\Resources\TestimonialResource\Pages\CreateTestimonial;
use App\Filament\Admin\Resources\TestimonialResource\Pages\EditTestimonial;
use App\Filament\Admin\Resources\TestimonialResource\Pages\ListTestimonials;
use App\Models\Admin;
use App\Models\ContactUs;
use App\Models\MediaInquiry;
use App\Models\RequestInformation;
use App\Models\Testimonial;
use App\Models\User;
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
});

// ─── Contact Us inbox ─────────────────────────────────────────────────

test('contact inbox page renders', function () {
    $this->get('/admin/contact-us')->assertSuccessful();
});

test('contact inbox lists existing entries with status badge', function () {
    ContactUs::create([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '555-0100',
        'reason' => 'Support',
        'message' => 'Help please',
        'status' => 1,
        'ip' => '127.0.0.1',
        'date_time' => '09/27/26 10:00:00 am',
    ]);

    Livewire::test(ListContactUs::class)
        ->assertCanRenderTableColumn('name')
        ->assertCanRenderTableColumn('status')
        ->assertCanRenderTableColumn('email');
});

test('admin can mark a contact entry as previewed', function () {
    $contact = ContactUs::create([
        'name' => 'John Smith',
        'email' => 'john@example.com',
        'phone' => '555-0101',
        'reason' => 'Sales',
        'message' => 'Interested',
        'status' => 1,
        'ip' => '127.0.0.1',
        'date_time' => '09/27/26 10:00:00 am',
    ]);

    Livewire::test(EditContactUs::class, ['record' => $contact->id])
        ->set('data.status', 0)
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('contact_us', ['id' => $contact->id, 'status' => 0]);
});

test('admin can delete a contact entry', function () {
    $contact = ContactUs::create([
        'name' => 'Delete Me',
        'email' => 'del@example.com',
        'phone' => '555-0102',
        'reason' => 'Spam',
        'message' => 'noise',
        'status' => 1,
        'ip' => '127.0.0.1',
        'date_time' => '09/27/26 10:00:00 am',
    ]);

    Livewire::test(ListContactUs::class)
        ->callTableAction('delete', $contact);

    $this->assertDatabaseMissing('contact_us', ['id' => $contact->id]);
});

// ─── Media Inquiries inbox ────────────────────────────────────────────

test('media inquiries inbox page renders', function () {
    $this->get('/admin/media-inquiries')->assertSuccessful();
});

test('media inquiries inbox lists existing entries', function () {
    MediaInquiry::create([
        'media' => 'CNN',
        'contact' => 'John Doe',
        'email' => 'john@cnn.com',
        'phone' => '555-0103',
        'story_concept' => 'IT story',
        'press_deadline' => '2026-10-01',
        'story_details' => 'Details here',
        'best_contact' => 'Email',
        'media_status' => 0,
    ]);

    Livewire::test(ListMediaInquiries::class)
        ->assertCanRenderTableColumn('media')
        ->assertCanRenderTableColumn('media_status');
});

test('admin can update media inquiry status', function () {
    $inquiry = MediaInquiry::create([
        'media' => 'Fox',
        'contact' => 'Reporter',
        'email' => 'r@fox.com',
        'phone' => '555-0104',
        'story_concept' => 'Concept',
        'press_deadline' => '2026-10-02',
        'story_details' => 'Details',
        'best_contact' => 'Phone',
        'media_status' => 0,
    ]);

    Livewire::test(EditMediaInquiry::class, ['record' => $inquiry->id])
        ->set('data.media_status', '1')
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('media_inquiries', ['id' => $inquiry->id, 'media_status' => '1']);
});

// ─── Request Information inbox ────────────────────────────────────────

test('request information inbox page renders', function () {
    $this->get('/admin/request-information')->assertSuccessful();
});

test('request information inbox lists existing entries', function () {
    RequestInformation::create([
        'first_name' => 'Ada',
        'last_name' => 'Lovelace',
        'company' => 'Analytical Engines',
        'email' => 'ada@example.com',
        'phone' => '555-0105',
        'status' => 0,
        'ip' => '127.0.0.1',
    ]);

    Livewire::test(ListRequestInformations::class)
        ->assertCanRenderTableColumn('first_name')
        ->assertCanRenderTableColumn('email');
});

// ─── Testimonials ─────────────────────────────────────────────────────

test('testimonials page renders', function () {
    $this->get('/admin/testimonials')->assertSuccessful();
});

test('testimonials list shows existing entries', function () {
    $user = User::create([
        'firstname' => 'Happy',
        'lastname' => 'Customer',
        'email' => 'happy@example.com',
        'password' => bcrypt('password'),
        'date_added' => now(),
    ]);

    Testimonial::create([
        'customer_id' => $user->user_id,
        'service_used' => 'Managed IT',
        'message' => 'Great service',
        'status' => 1,
        'published' => now(),
    ]);

    Livewire::test(ListTestimonials::class)
        ->assertCanRenderTableColumn('service_used')
        ->assertCanRenderTableColumn('status');
});

test('admin can create a testimonial', function () {
    $user = User::create([
        'firstname' => 'New',
        'lastname' => 'Customer',
        'email' => 'new.customer@example.com',
        'password' => bcrypt('password'),
        'date_added' => now(),
    ]);

    Livewire::test(CreateTestimonial::class)
        ->set('data.customer_id', $user->user_id)
        ->set('data.service_used', 'Cloud Migration')
        ->set('data.message', 'Excellent work')
        ->set('data.status', 1)
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('testimony_form', [
        'customer_id' => $user->user_id,
        'service_used' => 'Cloud Migration',
        'status' => 1,
    ]);
});

test('admin can approve a pending testimonial', function () {
    $user = User::create([
        'firstname' => 'Pending',
        'lastname' => 'User',
        'email' => 'pending@example.com',
        'password' => bcrypt('password'),
        'date_added' => now(),
    ]);

    $testimonial = Testimonial::create([
        'customer_id' => $user->user_id,
        'service_used' => 'Backup',
        'message' => 'Solid',
        'status' => 0,
        'published' => now(),
    ]);

    Livewire::test(
        EditTestimonial::class,
        ['record' => $testimonial->id]
    )
        ->set('data.status', 1)
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('testimony_form', ['id' => $testimonial->id, 'status' => 1]);
});
