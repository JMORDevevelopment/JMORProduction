<?php

use App\Models\User;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);
});

function createFrontendUser(array $overrides = []): User
{
    return User::create(array_merge([
        'firstname' => 'Jane',
        'lastname' => 'Doe',
        'email' => 'jane@example.com',
        'password' => bcrypt('secret-password'),
        'date_added' => now()->toDateString(),
    ], $overrides));
}

test('login authenticates a user with a bcrypt password', function () {
    createFrontendUser();

    $response = $this->post('/login', [
        'email' => 'jane@example.com',
        'password' => 'secret-password',
    ]);

    $response->assertRedirect(route('checkout'));
    $this->assertAuthenticated();
});

test('login upgrades a legacy md5 password to bcrypt on success', function () {
    createFrontendUser(['password' => md5('legacy-password')]);

    $this->post('/login', [
        'email' => 'jane@example.com',
        'password' => 'legacy-password',
    ])->assertRedirect(route('checkout'));

    $user = User::where('email', 'jane@example.com')->first();
    expect($user->password)->toStartWith('$2y$');
    expect(password_verify('legacy-password', $user->password))->toBeTrue();
});

test('login rejects wrong credentials', function () {
    createFrontendUser();

    $response = $this->post('/login', [
        'email' => 'jane@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('login is throttled after five failed attempts', function () {
    createFrontendUser();

    foreach (range(1, 5) as $attempt) {
        $this->post('/login', [
            'email' => 'jane@example.com',
            'password' => 'wrong-password',
        ]);
    }

    $response = $this->post('/login', [
        'email' => 'jane@example.com',
        'password' => 'secret-password',
    ]);

    $response->assertSessionHasErrors('email');
    expect(session('errors')->first('email'))->toContain('Too many login attempts');
    $this->assertGuest();
});

test('sign up stores a bcrypt password', function () {
    $response = $this->post('/sign-up/validate', [
        'firstname' => 'Jane',
        'lastname' => 'Doe',
        'email' => 'new-user@example.com',
        'password' => 'secret-password',
        'address' => '123 Main Street',
        'city' => 'Springfield',
        'state' => 'IL',
        'zip' => '62704',
    ]);

    $response->assertRedirect(route('checkout'));

    $user = User::where('email', 'new-user@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->password)->toStartWith('$2y$');
    expect(password_verify('secret-password', $user->password))->toBeTrue();
    $this->assertAuthenticatedAs($user);
});

test('forgot password sends a reset link for a known email', function () {
    Notification::fake();
    $user = createFrontendUser();

    $response = $this->post('/forgot-pass', ['email' => 'jane@example.com']);

    $response->assertRedirect(route('login', ['reset_pass' => 'yes']));
    Notification::assertSentTo($user, ResetPasswordNotification::class);
});

test('forgot password uses a uniform response for an unknown email', function () {
    Notification::fake();

    $response = $this->post('/forgot-pass', ['email' => 'nobody@example.com']);

    $response->assertRedirect(route('login', ['reset_pass' => 'yes']));
    Notification::assertNothingSent();
});

test('reset password works with a valid token', function () {
    $user = createFrontendUser();
    $token = Password::broker()->createToken($user);

    $response = $this->post('/reset-password', [
        'token' => $token,
        'email' => 'jane@example.com',
        'password' => 'brand-new-password',
        'password_confirmation' => 'brand-new-password',
    ]);

    $response->assertRedirect(route('login', ['reset_pass' => 'done']));

    $user->refresh();
    expect(password_verify('brand-new-password', $user->password))->toBeTrue();

    $this->post('/login', [
        'email' => 'jane@example.com',
        'password' => 'brand-new-password',
    ])->assertRedirect(route('checkout'));
});

test('reset password rejects an invalid token', function () {
    $user = createFrontendUser();
    Password::broker()->createToken($user);

    $response = $this->post('/reset-password', [
        'token' => 'invalid-token',
        'email' => 'jane@example.com',
        'password' => 'brand-new-password',
        'password_confirmation' => 'brand-new-password',
    ]);

    $response->assertSessionHasErrors('email');

    $user->refresh();
    expect(password_verify('brand-new-password', $user->password))->toBeFalse();
    expect(password_verify('secret-password', $user->password))->toBeTrue();
});
