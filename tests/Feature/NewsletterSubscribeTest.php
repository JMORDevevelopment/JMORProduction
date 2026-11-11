<?php

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
