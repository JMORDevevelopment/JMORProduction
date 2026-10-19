<?php

use App\Filament\Admin\Resources\AdminUserResource\Pages\CreateAdminUser;
use App\Models\Admin;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);

    $this->superAdmin = Admin::create([
        'firstname' => 'Super',
        'lastname' => 'Admin',
        'email' => 'rbac.super@test.com',
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
        'firstname' => 'Content',
        'lastname' => 'Editor',
        'email' => 'rbac.editor@test.com',
        'password' => bcrypt('password'),
        'status' => 1,
        'role' => 2,
        'image' => '',
        'last_login' => now(),
        'date_register' => now(),
    ]);

    $this->editor->forceFill([
        'two_factor_secret' => encrypt('test-two-factor-secret'),
        'two_factor_confirmed_at' => now(),
    ])->save();
    $this->editor->setTwoFactorChallengePassed();

    $this->editorRole = Role::create(['name' => 'content_editor', 'guard_name' => 'admin']);
});

test('a role with granted permissions allows access, while ungranted resources stay forbidden', function () {
    Permission::firstOrCreate(['name' => 'ViewAny:Blog', 'guard_name' => 'admin']);
    Permission::firstOrCreate(['name' => 'Create:Blog', 'guard_name' => 'admin']);

    $this->editorRole->givePermissionTo(['ViewAny:Blog', 'Create:Blog']);
    $this->editor->assignRole($this->editorRole);

    $this->actingAs($this->editor, 'admin');

    $this->get('/admin/blogs')->assertSuccessful();

    $this->get('/admin/transactions')->assertForbidden();
    $this->get('/admin/admin-users')->assertForbidden();
    $this->get('/admin/shield/roles')->assertForbidden();
    $this->get('/admin/settings')->assertForbidden();
});

test('custom permissions gate the settings, auth settings and backup abilities', function () {
    Permission::firstOrCreate(['name' => 'settings', 'guard_name' => 'admin']);
    Permission::firstOrCreate(['name' => 'authSettings', 'guard_name' => 'admin']);
    Permission::firstOrCreate(['name' => 'backup', 'guard_name' => 'admin']);

    expect(Gate::forUser($this->editor)->denies('settings'))->toBeTrue()
        ->and(Gate::forUser($this->editor)->denies('authSettings'))->toBeTrue()
        ->and(Gate::forUser($this->editor)->denies('backup'))->toBeTrue();

    $this->editorRole->givePermissionTo(['settings', 'authSettings', 'backup']);
    $this->editor->assignRole($this->editorRole);

    expect(Gate::forUser($this->editor)->allows('settings'))->toBeTrue()
        ->and(Gate::forUser($this->editor)->allows('authSettings'))->toBeTrue()
        ->and(Gate::forUser($this->editor)->allows('backup'))->toBeTrue();

    $this->actingAs($this->editor, 'admin');

    $this->get('/admin/settings')->assertSuccessful();
    $this->get('/admin/auth-settings')->assertSuccessful();
});

test('update status ability is grantable per role', function () {
    Permission::firstOrCreate(['name' => 'updateStatus', 'guard_name' => 'admin']);

    expect(Gate::forUser($this->editor)->denies('updateStatus'))->toBeTrue();

    $this->editorRole->givePermissionTo('updateStatus');
    $this->editor->assignRole($this->editorRole);

    expect(Gate::forUser($this->editor)->allows('updateStatus'))->toBeTrue();
});

test('admin accounts are permission driven, not super-only', function () {
    Permission::firstOrCreate(['name' => 'ViewAny:Admin', 'guard_name' => 'admin']);

    $this->actingAs($this->editor, 'admin');
    $this->get('/admin/admin-users')->assertForbidden();

    $this->editorRole->givePermissionTo('ViewAny:Admin');
    $this->editor->assignRole($this->editorRole);

    $this->actingAs($this->editor, 'admin');
    $this->get('/admin/admin-users')->assertSuccessful();
});

test('role management screen is accessible for super admins', function () {
    $this->actingAs($this->superAdmin, 'admin');

    $this->get('/admin/shield/roles')->assertSuccessful();
});

test('role management screen is forbidden for other admins', function () {
    $this->actingAs($this->editor, 'admin');

    $this->get('/admin/shield/roles')->assertForbidden();
});

test('creating an admin with a custom role syncs the role column and spatie role', function () {
    Role::findOrCreate('super_admin', 'admin');

    $this->actingAs($this->superAdmin, 'admin');

    Livewire::test(CreateAdminUser::class)
        ->fillForm([
            'firstname' => 'Custom',
            'lastname' => 'Role',
            'email' => 'custom.role@test.com',
            'password' => 'secret123',
            'role' => 'content_editor',
            'status' => 1,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $created = Admin::where('email', 'custom.role@test.com')->first();

    expect($created)->not->toBeNull()
        ->and((int) $created->role)->toBe(2)
        ->and($created->hasRole('content_editor'))->toBeTrue()
        ->and($created->hasRole('super_admin'))->toBeFalse();
});

test('the role select offers every role and defaults to super_admin', function () {
    Role::findOrCreate('super_admin', 'admin');

    $this->actingAs($this->superAdmin, 'admin');

    Livewire::test(CreateAdminUser::class)
        ->assertFormSet(['role' => 'super_admin']);
});
