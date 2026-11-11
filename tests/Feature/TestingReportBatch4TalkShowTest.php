<?php

use App\Filament\Admin\Resources\TalkShowResource\Pages\EditTalkShow;
use App\Filament\Admin\Resources\TalkShowResource\Pages\ListTalkShows;
use App\Mail\TalkShowApplicationReceived;
use App\Mail\TalkShowStatusChanged;
use App\Models\Admin;
use App\Models\TalkShow;
use App\Models\TalkShowSetting;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Livewire\Livewire;

function talkShowWizardPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Jane',
        'last_name' => 'Doe',
        'company_name' => 'Acme',
        'phone' => '(973) 555-1212',
        'email' => 'jane@example.com',
        'bio' => 'Long time radio producer',
        'work_name' => ['The Tonight Show'],
        'work_detail' => ['Guest appearance'],
        'weblink' => ['https://example.com/show'],
        'interview' => 'I want to talk about edge computing',
        'signature' => 'Jane Doe',
        'agreement_accepted' => '1',
        'counter' => '1',
        'firstNumber' => 5,
        'secondNumber' => 3,
    ], $overrides);
}

function postTalkShowWizard(array $overrides = []): TestResponse
{
    test()->get(route('contact'));
    $captcha = session('captcha_numbers');

    return test()->post(route('contact.interview'), array_merge(talkShowWizardPayload($overrides), [
        'protection_question' => $captcha[0] + $captcha[1],
    ]));
}

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);
});

// ─── Three-step layout ────────────────────────────────────────────────

test('talk show application renders a clear three step layout', function () {
    $html = $this->get(route('contact'))->assertSuccessful()->getContent();

    expect($html)
        ->toContain('data-step-pill="1"')
        ->toContain('data-step-pill="2"')
        ->toContain('data-step-pill="3"')
        ->toContain('1. Contact')
        ->toContain('2. Experience')
        ->toContain('3. Agreement')
        ->toContain('data-step="1"')
        ->toContain('data-step="2"')
        ->toContain('data-step="3"');
});

test('talk show application captures biography and appearances in the wizard', function () {
    $html = $this->get(route('contact'))->assertSuccessful()->getContent();

    expect($html)
        ->toContain('name="bio"')
        ->toContain('Show Appearance or Past Experience')
        ->toContain('id="add_work"')
        ->toContain('name="interview"');
});

test('talk show application offers agreement review and electronic signature fields', function () {
    $html = $this->get(route('contact'))->assertSuccessful()->getContent();

    expect($html)
        ->toContain('name="agreement_accepted"')
        ->toContain('name="signature"')
        ->toContain('Electronic Signature');
});

test('talk show wizard exposes accessible step navigation and labeled fields', function () {
    $html = $this->get(route('contact'))->assertSuccessful()->getContent();

    expect($html)
        ->toContain('for="signatureb"')
        ->toContain('for="phoneb"')
        ->toContain('id="qasubmitBtnb"')
        ->toContain('Submit Application')
        ->toContain('Drag the slider above to enable submit');
});

test('a failed submission repopulates previously added work entries', function () {
    postTalkShowWizard(['interview' => '']);

    $html = $this->get(route('contact'))->assertSuccessful()->getContent();

    expect($html)
        ->toContain('name="work_name[]"')
        ->toContain('value="The Tonight Show"');
});

// ─── Agreement upload by JMOR ─────────────────────────────────────────

test('uploaded guest agreement is offered for review on the application', function () {
    Storage::fake('public');
    Storage::disk('public')->put('talk-show/agreement.pdf', '%PDF-1.4 test');

    TalkShowSetting::create([
        'price' => '450.00',
        'question' => 'What is your show?',
        'agreement_path' => 'talk-show/agreement.pdf',
    ]);

    $html = $this->get(route('contact'))->assertSuccessful()->getContent();

    expect($html)
        ->toContain('JMOR guest agreement (PDF)')
        ->toContain('/storage/talk-show/agreement.pdf');
});

// ─── Signature + agreement validation ─────────────────────────────────

test('application requires an electronic signature', function () {
    $response = postTalkShowWizard(['signature' => '']);

    $response->assertSessionHasErrors('signature');
    $this->assertDatabaseMissing('talk_show', ['email' => 'jane@example.com']);
});

test('application requires agreement acceptance', function () {
    $response = postTalkShowWizard(['agreement_accepted' => null]);

    $response->assertSessionHasErrors('agreement_accepted');
    $this->assertDatabaseMissing('talk_show', ['email' => 'jane@example.com']);
});

// ─── Signed PDF storage + audit trail ─────────────────────────────────

test('successful application stores signature, signed pdf, and audit trail', function () {
    Storage::fake('local');
    Mail::fake();

    $response = postTalkShowWizard();

    $response->assertRedirect(route('contact'));
    $response->assertSessionHas('contact_success');

    $application = TalkShow::query()->where('email', 'jane@example.com')->firstOrFail();

    expect($application->signature)->toBe('Jane Doe')
        ->and((int) $application->status)->toBe(TalkShow::STATUS_SUBMITTED)
        ->and($application->signed_pdf_path)->not->toBeNull();

    Storage::disk('local')->assertExists($application->signed_pdf_path);

    $this->assertDatabaseHas('talk_show_audits', [
        'talk_show_id' => $application->id,
        'action' => 'submitted',
        'new_status' => TalkShow::STATUS_SUBMITTED,
    ]);

    Mail::assertSent(TalkShowApplicationReceived::class, function (TalkShowApplicationReceived $mail) use ($application) {
        return $mail->hasTo('jane@example.com')
            && $mail->application->is($application);
    });
});

test('signed pdf download is available to admins only', function () {
    Storage::fake('local');
    Mail::fake();

    postTalkShowWizard();
    $application = TalkShow::query()->where('email', 'jane@example.com')->firstOrFail();

    Storage::disk('local')->put($application->signed_pdf_path, '%PDF-1.4 signed');

    // Guests are redirected to login, not given the file.
    $this->get(route('admin.talk_show.signed_pdf', $application))
        ->assertRedirect(route('login'));

    $admin = makeAdmin();

    $this->actingAs($admin, 'admin')
        ->get(route('admin.talk_show.signed_pdf', $application))
        ->assertOk()
        ->assertDownload('talk-show-application-'.$application->id.'-signed.pdf');
});

// ─── Admin statuses + notifications ───────────────────────────────────

test('admin can list talk show applications with status badges', function () {
    TalkShow::create([
        'name' => 'Jane',
        'last_name' => 'Doe',
        'company_name' => 'Acme',
        'phone' => '555-1212',
        'email' => 'jane@example.com',
        'bio' => 'Producer',
        'work_name' => 'Show',
        'work_detail' => 'Detail',
        'weblink' => '',
        'interview' => 'Pitch',
        'signature' => 'Jane Doe',
        'ip' => '127.0.0.1',
        'date_time' => '11/09/26 10:00:00 am',
        'status' => TalkShow::STATUS_SUBMITTED,
    ]);

    $html = $this->actingAs(makeAdmin(), 'admin')
        ->get('/admin/talk-show-applications')
        ->assertSuccessful()
        ->getContent();

    expect($html)
        ->toContain('Submitted')
        ->toContain('jane@example.com');
});

test('admin status change writes an audit entry and notifies the applicant', function () {
    Storage::fake('local');
    Mail::fake();

    $application = TalkShow::create([
        'name' => 'Jane',
        'last_name' => 'Doe',
        'company_name' => 'Acme',
        'phone' => '555-1212',
        'email' => 'jane@example.com',
        'bio' => 'Producer',
        'work_name' => 'Show',
        'work_detail' => 'Detail',
        'weblink' => '',
        'interview' => 'Pitch',
        'signature' => 'Jane Doe',
        'ip' => '127.0.0.1',
        'date_time' => '11/09/26 10:00:00 am',
        'status' => TalkShow::STATUS_SUBMITTED,
    ]);

    Livewire::actingAs(makeAdmin(), 'admin')
        ->test(EditTalkShow::class, ['record' => $application->id])
        ->set('data.status', TalkShow::STATUS_ACCEPTED)
        ->call('save')
        ->assertHasNoErrors();

    $application->refresh();

    expect((int) $application->status)->toBe(TalkShow::STATUS_ACCEPTED);

    $this->assertDatabaseHas('talk_show_audits', [
        'talk_show_id' => $application->id,
        'action' => 'status_changed',
        'old_status' => TalkShow::STATUS_SUBMITTED,
        'new_status' => TalkShow::STATUS_ACCEPTED,
    ]);

    Mail::assertSent(TalkShowStatusChanged::class, function (TalkShowStatusChanged $mail) {
        return $mail->hasTo('jane@example.com')
            && $mail->newStatus === TalkShow::STATUS_ACCEPTED;
    });
});

test('admin can upload the guest agreement for applicant review', function () {
    Storage::fake('public');

    Livewire::actingAs(makeAdmin(), 'admin')
        ->test(ListTalkShows::class)
        ->callTableAction('uploadAgreement', null, [
            'agreement' => UploadedFile::fake()->create('guest-agreement.pdf', 100, 'application/pdf'),
        ])
        ->assertHasNoErrors();

    $settings = TalkShowSetting::query()->firstOrFail();

    expect($settings->agreement_path)->not->toBeNull();
    Storage::disk('public')->assertExists($settings->agreement_path);
});

// ─── Helper ───────────────────────────────────────────────────────────

function makeAdmin(): Admin
{
    $admin = Admin::create([
        'firstname' => 'Test',
        'lastname' => 'Admin',
        'email' => 'admin@talkshow.test',
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

    return $admin;
}
