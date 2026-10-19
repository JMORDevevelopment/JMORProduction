<?php

use App\Models\Admin;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Gate;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);

    $this->superAdmin = Admin::create([
        'firstname' => 'Super',
        'lastname' => 'Admin',
        'email' => 'super.admin@test.com',
        'password' => bcrypt('password'),
        'status' => 1,
        'role' => 1,
        'image' => '',
        'last_login' => now(),
        'date_register' => now(),
    ]);

    $this->superAdmin->forceFill([
        'two_factor_secret' => encrypt('test-two-factor-secret'),
        'two_factor_confirmed_at' => now(),
    ])->save();
    $this->superAdmin->setTwoFactorChallengePassed();

    $this->editor = Admin::create([
        'firstname' => 'Limited',
        'lastname' => 'Editor',
        'email' => 'limited.editor@test.com',
        'password' => bcrypt('password'),
        'status' => 1,
        'role' => 0,
        'image' => '',
        'last_login' => now(),
        'date_register' => now(),
    ]);

    $this->editor->forceFill([
        'two_factor_secret' => encrypt('test-two-factor-secret'),
        'two_factor_confirmed_at' => now(),
    ])->save();
    $this->editor->setTwoFactorChallengePassed();
});

test('super admins are allowed every ability', function () {
    $abilities = [
        'delete', 'forceDelete', 'deleteAny', 'restore', 'restoreAny',
        'backup', 'updateStatus', 'settings', 'authSettings',
        'ViewAny:Blog', 'Delete:Blog', 'ViewAny:Admin',
    ];

    foreach ($abilities as $ability) {
        expect(Gate::forUser($this->superAdmin)->allows($ability))->toBeTrue("expected allow for role 1 on {$ability}");
    }
});

test('non-super admins without permissions are denied every ability', function () {
    $abilities = [
        'delete', 'forceDelete', 'deleteAny', 'restore', 'restoreAny',
        'backup', 'updateStatus', 'settings', 'authSettings',
        'ViewAny:Blog', 'Delete:Blog', 'ViewAny:Admin',
    ];

    foreach ($abilities as $ability) {
        expect(Gate::forUser($this->editor)->denies($ability))->toBeTrue("expected denial without permission on {$ability}");
    }
});

test('non-super admin cannot open the settings page', function () {
    $this->actingAs($this->editor, 'admin');

    $this->get('/admin/settings')->assertForbidden();
});

test('super admin can open the settings page', function () {
    $this->actingAs($this->superAdmin, 'admin');

    $this->get('/admin/settings')->assertSuccessful();
});

test('non-super admin cannot open the auth settings page', function () {
    $this->actingAs($this->editor, 'admin');

    $this->get('/admin/auth-settings')->assertForbidden();
});

test('super admin can open the auth settings page', function () {
    $this->actingAs($this->superAdmin, 'admin');

    $this->get('/admin/auth-settings')->assertSuccessful();
});

test('super admin can open the dashboard', function () {
    $this->actingAs($this->superAdmin, 'admin');

    $this->get('/admin')->assertSuccessful();
});
