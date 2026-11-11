<?php

use App\Support\PrivacyPolicyContentFix;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);
});

/*
|--------------------------------------------------------------------------
| ISSUE 041 — Privacy policy content
|--------------------------------------------------------------------------
*/

test('privacy content fix corrects domain, removes chamber remarketing, updates date, fixes grammar', function () {
    $bad = <<<'HTML'
<p>The JMOR Connection, Inc. ("us", "we", or "our") operates the ourprintconnection.com website (the "Service").</p>
<p><strong>Behavioral Remarketing</strong></p>
<p>Franklin Lakes Chamber of Commerce uses remarketing services to advertise on third party web sites to you after you visited our Service. We, and our third-party vendors, use cookies to inform, optimize and serve ads based on your past visits to our Service.</p>
<p>Google AdWords remarketing service is provided by Google Inc.</p>
<p style="margin-left: .5in;">Facebook adheres the Self-Regulatory Principles for Online Behavioral Advertising.</p>
<p><strong>Do Not Track Disclosure</strong></p>
<p>The Help feature on most browsers provide information on how to accept cookies.</p>
<p>This Privacy Policy is effective as of March 01, 2019 and will remain in effect except with respect to any changes.</p>
HTML;

    DB::table('pages')->insert([
        'link' => 'privacy-policy',
        'name' => 'Privacy Policy',
        'priority' => 0,
        'slider_status' => 0,
        'menu_location' => 0,
        'description' => $bad,
        'image' => '',
        'form_id' => 0,
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
        'menu_status' => 0,
    ]);

    PrivacyPolicyContentFix::apply();

    $fixed = DB::table('pages')->where('link', 'privacy-policy')->value('description');

    expect($fixed)
        ->not->toContain('ourprintconnection.com')
        ->toContain('jmor.com')
        ->not->toContain('Franklin Lakes Chamber')
        ->not->toContain('Behavioral Remarketing')
        ->not->toContain('Facebook adheres the')
        ->toContain('Do Not Track Disclosure')
        ->not->toContain('March 01, 2019')
        ->toContain('January 01, 2026')
        ->not->toContain('browsers provide information')
        ->toContain('browsers provides information');
});

test('privacy policy page renders without the outdated references', function () {
    $bad = <<<'HTML'
<p>The JMOR Connection, Inc. ("us") operates the ourprintconnection.com website (the "Service").</p>
<p><strong>Behavioral Remarketing</strong></p>
<p>Franklin Lakes Chamber of Commerce uses remarketing services to advertise on third party web sites to you after you visited our Service.</p>
<p>Google AdWords remarketing service is provided by Google Inc.</p>
<p><strong>Do Not Track Disclosure</strong></p>
<p>The Help feature on most browsers provide information on how to accept cookies.</p>
<p>This Privacy Policy is effective as of March 01, 2019 and will remain in effect.</p>
HTML;

    DB::table('pages')->insert([
        'link' => 'privacy-policy',
        'name' => 'Privacy Policy',
        'priority' => 0,
        'slider_status' => 0,
        'menu_location' => 0,
        'description' => $bad,
        'image' => '',
        'form_id' => 0,
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
        'menu_status' => 0,
    ]);

    PrivacyPolicyContentFix::apply();

    $html = $this->get(route('privacy-policy'))->assertSuccessful()->getContent();

    expect($html)
        ->not->toContain('ourprintconnection.com')
        ->not->toContain('Franklin Lakes Chamber')
        ->not->toContain('March 01, 2019');
});

/*
|--------------------------------------------------------------------------
| ISSUE 057 — Cookie consent
|--------------------------------------------------------------------------
*/

test('every frontend page shows the cookie consent banner with the required choices', function () {
    $html = $this->get(route('home'))->assertSuccessful()->getContent();

    expect($html)
        ->toContain('id="jm-cookie-consent"')
        ->toContain('Accept All')
        ->toContain('Reject Optional')
        ->toContain('Customize')
        ->toContain('jmor_cookie_consent')
        ->toContain('data-jm-cookie-settings');
});

test('cookie consent banner discloses essential and optional cookie categories', function () {
    $html = $this->get(route('home'))->assertSuccessful()->getContent();

    expect($html)
        ->toContain('Essential (always on)')
        ->toContain('session, CSRF, language preference')
        ->toContain('YouTube')
        ->toContain('Google Maps');
});

test('footer exposes a cookie settings control to reopen preferences', function () {
    $html = $this->get(route('home'))->assertSuccessful()->getContent();

    expect($html)->toContain('data-jm-cookie-settings');
});

test('optional embeds are marked for consent gating on home and contact', function () {
    $home = $this->get(route('home'))->assertSuccessful()->getContent();
    $contact = $this->get(route('contact'))->assertSuccessful()->getContent();

    expect($home)
        ->toContain('data-optional-embed')
        ->toContain('youtube.com/embed');
    expect($contact)
        ->toContain('data-optional-embed')
        ->toContain('google.com/maps');
});

test('rejecting optional cookies does not redirect the visitor', function () {
    $html = $this->get(route('home'))->assertSuccessful()->getContent();

    // The reject handler only writes localStorage and hides the banner — no navigation.
    expect($html)
        ->toContain('data-jm-cookie-reject')
        ->not->toContain('window.location');
});

/*
|--------------------------------------------------------------------------
| ISSUE 060 — Cross-form data leakage
|--------------------------------------------------------------------------
*/

test('talk show validation failure does not repopulate the web inquiry form', function () {
    $response = $this->from(route('contact'))->post(route('contact.interview'), [
        'name' => 'TalkShowFirst',
        'last_name' => '',
        'phone' => '555-0100',
        'email' => 'talkshow@example.com',
        'bio' => 'Bio here',
        'work_name' => ['Show'],
        'work_detail' => ['Detail'],
        'weblink' => ['https://example.com'],
        'interview' => 'Pitch',
        'firstNumber' => 1,
        'secondNumber' => 1,
        'protection_question' => 999,
    ]);

    $response->assertRedirect(route('contact'));
    $response->assertSessionHasErrors();

    $html = $this->get(route('contact'))->assertSuccessful()->getContent();

    $inquiryForm = substr($html, strpos($html, 'id="form_one"'), strpos($html, 'id="form_two"') - strpos($html, 'id="form_one"'));
    $talkShowForm = substr($html, strpos($html, 'id="form_two"'));

    // Web inquiry must stay blank; talk show may repopulate its own shared fields
    expect($inquiryForm)
        ->not->toContain('value="TalkShowFirst"')
        ->not->toContain('value="talkshow@example.com"');
    expect($talkShowForm)
        ->toContain('value="TalkShowFirst"')
        ->toContain('value="talkshow@example.com"');
});

test('web inquiry validation failure does not repopulate the talk show form shared fields', function () {
    $this->get(route('contact'));
    $captcha = session('captcha_numbers');

    $response = $this->from(route('contact'))->post(route('contact.submit'), [
        'name' => 'InquiryName',
        'email' => 'inquiry@example.com',
        'phone' => '555-0200',
        'reason' => 'General Inquiry',
        'message' => 'Hello there',
        'firstNumber' => $captcha[0] ?? 1,
        'secondNumber' => $captcha[1] ?? 1,
        'protection_question' => 999,
    ]);

    $response->assertSessionHasErrors();

    $html = $this->get(route('contact'))->assertSuccessful()->getContent();

    $inquiryForm = substr($html, strpos($html, 'id="form_one"'), strpos($html, 'id="form_two"') - strpos($html, 'id="form_one"'));
    $talkShowForm = substr($html, strpos($html, 'id="form_two"'));

    // Inquiry may repopulate its own fields; talk show must stay blank for shared keys
    expect($inquiryForm)
        ->toContain('value="InquiryName"')
        ->toContain('value="inquiry@example.com"');
    expect($talkShowForm)
        ->not->toContain('value="InquiryName"')
        ->not->toContain('value="inquiry@example.com"');
});

test('login never repopulates the password field', function () {
    $html = $this->get(route('login'))->assertSuccessful()->getContent();

    expect($html)
        ->toContain('name="password"')
        ->not->toContain("old('password')");
});

test('a fresh contact page starts with both forms blank for shared fields', function () {
    $html = $this->get(route('contact'))->assertSuccessful()->getContent();

    // No old input: inquiry name is empty string default
    expect($html)->toContain('value=""');
});
