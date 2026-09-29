<?php

use App\Mail\AdminPasswordReset;
use App\Models\Admin;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
});

function extractResetToken(AdminPasswordReset $mail): string
{
    $segment = parse_url($mail->resetUrl, PHP_URL_PATH);

    return basename($segment);
}

test('admin forgot password page renders', function () {
    $this->get('/admin/forgot-password')
        ->assertSuccessful()
        ->assertSee('Reset your admin password');
});

test('requesting a reset for an existing admin stores a token and sends mail', function () {
    Mail::fake();

    $this->post('/admin/forgot-password', ['email' => 'admin@test.com'])
        ->assertRedirect(route('admin.forgot-password'));

    Mail::assertSent(AdminPasswordReset::class, function (AdminPasswordReset $mail) {
        return $mail->hasTo('admin@test.com')
            && str_contains($mail->resetUrl, '/admin/reset-password/');
    });

    $this->assertDatabaseHas('password_reset_tokens', ['email' => 'admin@test.com']);
});

test('requesting a reset for an unknown email behaves the same but sends nothing', function () {
    Mail::fake();

    $this->post('/admin/forgot-password', ['email' => 'nobody@test.com'])
        ->assertRedirect(route('admin.forgot-password'));

    Mail::assertNothingSent();
    $this->assertDatabaseMissing('password_reset_tokens', ['email' => 'nobody@test.com']);
});

test('reset link form renders for a valid token', function () {
    Mail::fake();

    $this->post('/admin/forgot-password', ['email' => 'admin@test.com']);

    $token = extractResetToken(Mail::sent(AdminPasswordReset::class)->first());

    $this->get(route('admin.reset-password', $token))
        ->assertSuccessful()
        ->assertSee('Choose a new admin password');
});

test('an invalid token redirects back to the forgot password page', function () {
    $this->get(route('admin.reset-password', 'totally-invalid-token'))
        ->assertRedirect(route('admin.forgot-password'));
});

test('an expired token is rejected', function () {
    DB::table('password_reset_tokens')->insert([
        'email' => 'admin@test.com',
        'token' => hash('sha256', 'expired-token'),
        'created_at' => now()->subMinutes(120),
    ]);

    $this->get(route('admin.reset-password', 'expired-token'))
        ->assertRedirect(route('admin.forgot-password'));
});

test('completing the reset sets a new password and deletes the token', function () {
    Mail::fake();

    $this->post('/admin/forgot-password', ['email' => 'admin@test.com']);

    $token = extractResetToken(Mail::sent(AdminPasswordReset::class)->last());

    $this->post(route('admin.reset-password.update'), [
        'token' => $token,
        'password' => 'newsecret123',
        'password_confirmation' => 'newsecret123',
    ])->assertRedirect(route('filament.admin.auth.login'));

    $this->admin->refresh();

    expect(Hash::check('newsecret123', $this->admin->password))->toBeTrue()
        ->and(Hash::check('password', $this->admin->password))->toBeFalse();

    $this->assertDatabaseMissing('password_reset_tokens', ['email' => 'admin@test.com']);
});

test('admin login page links to the forgot password page', function () {
    $this->get(route('filament.admin.auth.login'))
        ->assertSuccessful()
        ->assertSee('Forgot password?');
});
