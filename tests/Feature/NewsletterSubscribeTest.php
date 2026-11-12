<?php

use App\Mail\NewsletterConfirmation;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    $path = storage_path('app/newsletter_subscribers.json');
    if (is_file($path)) {
        unlink($path);
    }
});

afterEach(function () {
    $path = storage_path('app/newsletter_subscribers.json');
    if (is_file($path)) {
        unlink($path);
    }
});

test('newsletter form posts to the local handler when no Mailchimp action is configured', function () {
    config(['newsletter.action' => null]);

    $html = $this->get(route('contact'))->assertSuccessful()->getContent();

    expect($html)
        ->toContain('action="'.route('newsletter.subscribe').'"')
        ->toContain('name="FNAME"')
        ->toContain('name="LNAME"')
        ->toContain('name="EMAIL"')
        ->toContain('name="NEWSLETTER_CONSENT"');
});

test('newsletter form posts to Mailchimp when NEWSLETTER_FORM_ACTION is set', function () {
    config(['newsletter.action' => 'https://example.us20.list-manage.com/subscribe/post?u=abc&id=def']);

    $html = $this->get(route('contact'))->assertSuccessful()->getContent();

    expect($html)->toContain('action="https://example.us20.list-manage.com/subscribe/post?u=abc&amp;id=def"');
});

test('a valid newsletter signup is stored and confirmed', function () {
    $response = $this->post(route('newsletter.subscribe'), [
        'FNAME' => 'Jane',
        'LNAME' => 'Doe',
        'EMAIL' => 'newsletter@example.com',
        'NEWSLETTER_CONSENT' => 'I agree',
    ]);

    $response->assertRedirect()->assertSessionHas('newsletter_success');

    $rows = json_decode(file_get_contents(storage_path('app/newsletter_subscribers.json')), true);
    $match = array_filter($rows, fn ($row) => $row['email'] === 'newsletter@example.com');

    expect($match)->not->toBeEmpty()
        ->and(reset($match)['first_name'])->toBe('Jane')
        ->and(reset($match)['status'])->toBe('pending');
});

test('a confirmation email is sent to the subscriber', function () {
    Mail::fake();

    $this->post(route('newsletter.subscribe'), [
        'FNAME' => 'Jane',
        'LNAME' => 'Doe',
        'EMAIL' => 'confirm-me@example.com',
        'NEWSLETTER_CONSENT' => 'I agree',
    ])->assertSessionHas('newsletter_success');

    Mail::assertSent(NewsletterConfirmation::class, function ($mail) {
        return $mail->hasTo('confirm-me@example.com')
            && $mail->firstName === 'Jane'
            && $mail->envelope()->subject === 'Please confirm your JMOR newsletter subscription'
            && preg_match('#/newsletter/confirm/[A-Za-z0-9]{40}$#', $mail->confirmUrl) === 1;
    });
});

test('the confirmation email links to a real confirm endpoint', function () {
    Mail::fake();

    $this->post(route('newsletter.subscribe'), [
        'FNAME' => 'Jane',
        'LNAME' => 'Doe',
        'EMAIL' => 'link-check@example.com',
        'NEWSLETTER_CONSENT' => 'I agree',
    ]);

    $confirmUrl = null;
    Mail::assertSent(NewsletterConfirmation::class, function ($mail) use (&$confirmUrl) {
        $confirmUrl = $mail->confirmUrl;

        return true;
    });

    $this->get($confirmUrl)
        ->assertSuccessful()
        ->assertSee("You're confirmed!", false);
});

test('confirming a token marks the subscriber confirmed', function () {
    Mail::fake();

    $this->post(route('newsletter.subscribe'), [
        'FNAME' => 'Jane',
        'LNAME' => 'Doe',
        'EMAIL' => 'confirm-flow@example.com',
        'NEWSLETTER_CONSENT' => 'I agree',
    ]);

    $rows = json_decode(file_get_contents(storage_path('app/newsletter_subscribers.json')), true);
    $row = collect($rows)->firstWhere('email', 'confirm-flow@example.com');
    expect($row['status'])->toBe('pending')
        ->and($row['token'])->not->toBeEmpty();

    $this->get(route('newsletter.confirm', ['token' => $row['token']]))
        ->assertSuccessful()
        ->assertSee("You're confirmed!", false);

    $rows = json_decode(file_get_contents(storage_path('app/newsletter_subscribers.json')), true);
    $row = collect($rows)->firstWhere('email', 'confirm-flow@example.com');
    expect($row['status'])->toBe('confirmed')
        ->and($row['confirmed_at'])->not->toBeEmpty();
});

test('an invalid confirm token shows an error page', function () {
    $this->get(route('newsletter.confirm', ['token' => str_repeat('a', 40)]))
        ->assertSuccessful()
        ->assertSee('Invalid confirmation link', false);
});

test('confirming twice is safe and reports already confirmed', function () {
    Mail::fake();

    $this->post(route('newsletter.subscribe'), [
        'FNAME' => 'Jane',
        'LNAME' => 'Doe',
        'EMAIL' => 'double-confirm@example.com',
        'NEWSLETTER_CONSENT' => 'I agree',
    ]);

    $rows = json_decode(file_get_contents(storage_path('app/newsletter_subscribers.json')), true);
    $token = collect($rows)->firstWhere('email', 'double-confirm@example.com')['token'];

    $this->get(route('newsletter.confirm', ['token' => $token]))
        ->assertSee("You're confirmed!", false);

    $this->get(route('newsletter.confirm', ['token' => $token]))
        ->assertSuccessful()
        ->assertSee('Already confirmed', false);
});

test('re-subscribing after confirm says already subscribed and does not resend pending flow', function () {
    Mail::fake();

    $payload = [
        'FNAME' => 'Jane',
        'LNAME' => 'Doe',
        'EMAIL' => 'already@example.com',
        'NEWSLETTER_CONSENT' => 'I agree',
    ];

    $this->post(route('newsletter.subscribe'), $payload);
    $rows = json_decode(file_get_contents(storage_path('app/newsletter_subscribers.json')), true);
    $token = collect($rows)->firstWhere('email', 'already@example.com')['token'];
    $this->get(route('newsletter.confirm', ['token' => $token]));

    Mail::fake();
    $response = $this->from(route('contact'))->post(route('newsletter.subscribe'), $payload);
    $response->assertRedirect(route('contact'));
    $response->assertSessionHas('newsletter_success', 'You are already subscribed to the JMOR newsletter.');
    Mail::assertNothingSent();
});

test('duplicate newsletter signups do not create a second subscriber', function () {
    $payload = [
        'FNAME' => 'Jane',
        'LNAME' => 'Doe',
        'EMAIL' => 'dup@example.com',
        'NEWSLETTER_CONSENT' => 'I agree',
    ];

    $this->post(route('newsletter.subscribe'), $payload)->assertSessionHas('newsletter_success');
    $this->post(route('newsletter.subscribe'), $payload)->assertSessionHas('newsletter_success');

    $rows = json_decode(file_get_contents(storage_path('app/newsletter_subscribers.json')), true);
    $count = count(array_filter($rows, fn ($row) => $row['email'] === 'dup@example.com'));

    expect($count)->toBe(1);
});

test('newsletter signup requires all fields and consent', function () {
    $response = $this->from(route('contact'))->post(route('newsletter.subscribe'), [
        'FNAME' => '',
        'LNAME' => '',
        'EMAIL' => 'not-an-email',
    ]);

    $response->assertRedirect(route('contact'));
    $response->assertSessionHasErrors(['FNAME', 'LNAME', 'EMAIL', 'NEWSLETTER_CONSENT']);
});

test('validation errors are shown next to the newsletter form', function () {
    $page = $this->from(route('contact'))
        ->followingRedirects()
        ->post(route('newsletter.subscribe'), ['EMAIL' => 'bad'])
        ->assertSuccessful()
        ->getContent();

    expect($page)
        ->toContain('alert-danger')
        ->toContain('First Name:')
        ->toContain('Consent:');
});
