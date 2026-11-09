<?php

use App\Models\Admin;
use App\Models\ContactUs;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);

    DB::table('settings')->insert([
        ['option' => 'email', 'value' => 'admin@test.com'],
        ['option' => 'phone', 'value' => '(973) 394-1000'],
        ['option' => 'address', 'value' => '799 Franklin Ave Unit 3'],
    ]);
});

function batch4InterviewPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Jane',
        'last_name' => 'Doe',
        'company_name' => 'Acme',
        'phone' => '(973) 555-1212 x108',
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

function batch4CaptchaPost(string $route, array $payload): TestResponse
{
    test()->get(route('contact'));
    $captcha = session('captcha_numbers');

    return test()->post(route($route), array_merge($payload, [
        'protection_question' => $captcha[0] + $captcha[1],
    ]));
}

test('inquiry form stores the submission and flashes the success message', function () {
    $response = batch4CaptchaPost('contact.submit', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '(973) 555-1212',
        'reason' => 'General Inquiry',
        'message' => 'Hello there',
    ]);

    $response->assertRedirect(route('contact'));
    $response->assertSessionHas('contact_success');
    $this->assertDatabaseHas('contact_us', ['email' => 'jane@example.com']);
});

test('inquiry form accepts a US phone number with an extension and normalizes spaces', function () {
    $response = batch4CaptchaPost('contact.submit', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '(973) 555-1212   x108',
        'reason' => 'General Inquiry',
        'message' => 'Hello there',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('contact_us', [
        'email' => 'jane@example.com',
        'phone' => '(973) 555-1212 x108',
    ]);
});

test('inquiry form rejects a phone number containing letters', function () {
    $response = batch4CaptchaPost('contact.submit', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => 'call me maybe',
        'reason' => 'General Inquiry',
        'message' => 'Hello there',
    ]);

    $response->assertSessionHasErrors('phone');
    $this->assertDatabaseMissing('contact_us', ['email' => 'jane@example.com']);
});

test('talk show application accepts a US phone number with an extension', function () {
    $response = batch4CaptchaPost('contact.interview', batch4InterviewPayload([
        'phone' => '(973) 555-1212 ext. 108',
    ]));

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('contact'));
    $this->assertDatabaseHas('talk_show', [
        'email' => 'jane@example.com',
        'phone' => '(973) 555-1212 ext. 108',
    ]);
});

test('a successful submission renders the thank you screen with phone and countdown', function () {
    $html = $this->withSession(['contact_success' => 'Message is sent we will contact you soon.'])
        ->get(route('contact'))
        ->assertSuccessful()
        ->getContent();

    expect($html)->toContain('id="contact-thanks"');
    expect($html)->toContain('Thank you!');
    expect($html)->toContain('Need help right away?');
    expect($html)->toContain('href="tel:+19733941000"');
    expect($html)->toContain('id="thanks-count"');
    expect($html)->toContain('id="thanks-chat"');
});

test('the contact page hides the thank you screen without a success flash', function () {
    $this->get(route('contact'))
        ->assertSuccessful()
        ->assertDontSee('id="contact-thanks"');
});

test('the US resident toggle appears only on the talk show application', function () {
    $html = $this->get(route('contact'))->assertSuccessful()->getContent();

    expect(substr_count($html, 'US Resident'))->toBe(1);
    expect(strpos($html, 'US Resident'))->toBeGreaterThan(strpos($html, 'id="form_two"'));
});

test('both forms render the drag to verify captcha control', function () {
    $this->get(route('contact'))
        ->assertSuccessful()
        ->assertSee('id="captcha"', false)
        ->assertSee('id="captchab"', false)
        ->assertSee('Drag To Verify');
});

test('action tiles are keyboard reachable buttons that scroll and focus the forms', function () {
    $this->get(route('contact'))
        ->assertSuccessful()
        ->assertSee('<button type="button" class="contact-action border-0 bg-transparent w-100"', false)
        ->assertSee('function scrollToForm', false)
        ->assertSee('function chatNow()', false)
        ->assertSee('scroll-margin-top', false);
});

test('the add work control reports inline errors and treats the web link as optional', function () {
    $this->get(route('contact'))
        ->assertSuccessful()
        ->assertSee('id="work_error"', false)
        ->assertSee('Add Work or Experience', false)
        ->assertSee('Please enter a show or experience name.', false)
        ->assertSee('(optional) https:// or http://', false);
});

test('newsletter signup requires first name, last name, email, and consent only', function () {
    $html = $this->get(route('contact'))->assertSuccessful()->getContent();

    expect($html)->toContain('name="FNAME"');
    expect($html)->toContain('name="LNAME"');
    expect($html)->toContain('name="EMAIL"');
    expect($html)->toContain('id="mc-embedded-subscribe-consent"');
    expect($html)->toContain('href="'.route('privacy-policy').'"');

    // The old single Full Name field is gone.
    expect($html)->not->toContain('name="NAME"');
    expect($html)->not->toContain('mce-NAME');
});

test('the talk show guest application stays publicly reachable', function () {
    $this->get(route('contact'))
        ->assertSuccessful()
        ->assertSee('id="form_two"', false)
        ->assertSee('action="'.route('contact.interview').'"', false)
        ->assertSee('Talk Show Guest', false);

    // No auth gate on the interview endpoint itself.
    $this->post(route('contact.interview'), [])
        ->assertSessionHasErrors(['name', 'last_name', 'phone', 'email', 'bio', 'work_name', 'interview']);
});

test('contact form offers the distinct workflow reasons', function () {
    $html = $this->get(route('contact'))->assertSuccessful()->getContent();

    expect($html)->toContain('Employment &amp; Internships');
    expect($html)->toContain('Technology Assessment');
    expect($html)->toContain('Residential Support');
    expect($html)->toContain('Business Support');
    expect($html)->toContain('id="resume_group"');
    expect($html)->toContain('id="assessment_group"');
    expect($html)->toContain('enctype="multipart/form-data"');
});

test('employment submission without a resume is rejected', function () {
    $response = batch4CaptchaPost('contact.submit', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '(973) 555-1212',
        'reason' => 'Employment & Internships',
        'message' => 'I would like to apply',
    ]);

    $response->assertSessionHasErrors('resume');
    $this->assertDatabaseMissing('contact_us', ['email' => 'jane@example.com']);
});

test('employment submission stores a pdf resume on the private disk', function () {
    $response = batch4CaptchaPost('contact.submit', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '(973) 555-1212',
        'reason' => 'Employment & Internships',
        'message' => 'I would like to apply',
        'resume' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('contact'));

    $record = ContactUs::where('email', 'jane@example.com')->first();
    expect($record)->not->toBeNull();
    expect($record->resume_path)->not->toBeNull();
    expect(Storage::disk('local')->exists($record->resume_path))->toBeTrue();
    // Never on the public disk.
    expect(Storage::disk('public')->exists($record->resume_path))->toBeFalse();
});

test('technology assessment requires a scope', function () {
    $response = batch4CaptchaPost('contact.submit', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '(973) 555-1212',
        'reason' => 'Technology Assessment',
        'message' => 'Please assess our office',
    ]);

    $response->assertSessionHasErrors('assessment_scope');
});

test('business assessment requires business name and type', function () {
    $response = batch4CaptchaPost('contact.submit', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '(973) 555-1212',
        'reason' => 'Technology Assessment',
        'message' => 'Please assess our office',
        'assessment_scope' => 'business',
    ]);

    $response->assertSessionHasErrors(['business_name', 'business_type']);
});

test('business assessment stores the workflow fields', function () {
    $response = batch4CaptchaPost('contact.submit', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '(973) 555-1212',
        'reason' => 'Technology Assessment',
        'message' => 'Please assess our office',
        'assessment_scope' => 'business',
        'business_name' => 'Acme Retail',
        'business_type' => 'Retail',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('contact_us', [
        'email' => 'jane@example.com',
        'reason' => 'Technology Assessment',
        'assessment_scope' => 'business',
        'business_name' => 'Acme Retail',
        'business_type' => 'Retail',
    ]);
});

test('residential assessment stores without business fields', function () {
    $response = batch4CaptchaPost('contact.submit', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '(973) 555-1212',
        'reason' => 'Technology Assessment',
        'message' => 'Please assess my home',
        'assessment_scope' => 'residential',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('contact_us', [
        'email' => 'jane@example.com',
        'assessment_scope' => 'residential',
        'business_name' => null,
    ]);
});

test('admin contact inbox exposes the distinct workflow sections', function () {
    $admin = Admin::create([
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
    $admin->forceFill([
        'two_factor_secret' => encrypt('test-two-factor-secret'),
        'two_factor_confirmed_at' => now(),
    ])->save();
    $admin->setTwoFactorChallengePassed();

    $html = $this->actingAs($admin, 'admin')->get('/admin/contact-us')->assertSuccessful()->getContent();

    expect($html)->toContain('General');
    expect($html)->toContain('Technology Assessment');
    expect($html)->toContain('Support');
    expect($html)->toContain('Employment');
});
