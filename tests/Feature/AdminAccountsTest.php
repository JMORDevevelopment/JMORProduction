<?php

use App\Filament\Admin\Resources\AdminUserResource\Pages\CreateAdminUser;
use App\Filament\Admin\Resources\AdminUserResource\Pages\EditAdminUser;
use App\Filament\Admin\Resources\AdminUserResource\Pages\ListAdminUsers;
use App\Filament\Admin\Resources\UserGroupResource\Pages\CreateUserGroup;
use App\Filament\Admin\Resources\UserGroupResource\Pages\EditUserGroup;
use App\Filament\Admin\Resources\UserGroupResource\Pages\ListUserGroups;
use App\Filament\Admin\Resources\UserResource\Pages\CreateUser;
use App\Filament\Admin\Resources\UserResource\Pages\EditUser;
use App\Filament\Admin\Resources\UserResource\Pages\ListUsers;
use App\Models\Admin;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Hash;
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

// ─── Admin accounts ──────────────────────────────────────────────────

test('admin accounts page renders', function () {
    $this->get('/admin/admin-users')->assertSuccessful();
});

test('admin accounts page is forbidden for simple users', function () {
    $simple = Admin::create([
        'firstname' => 'Simple',
        'lastname' => 'User',
        'email' => 'simple@test.com',
        'password' => bcrypt('password'),
        'status' => 1,
        'role' => 0,
        'image' => '',
        'last_login' => now(),
        'date_register' => now(),
    ]);

    $simple->forceFill([
        'two_factor_secret' => encrypt('test-two-factor-secret'),
        'two_factor_confirmed_at' => now(),
    ])->save();
    $simple->setTwoFactorChallengePassed();

    $this->actingAs($simple, 'admin');

    $this->get('/admin/admin-users')->assertForbidden();
});

test('super admin can create an admin account with bcrypt password', function () {
    Livewire::test(CreateAdminUser::class)
        ->fillForm([
            'firstname' => 'Super',
            'lastname' => 'Root',
            'email' => 'root@test.com',
            'password' => 'secret123',
            'role' => 1,
            'status' => 1,
        ])
        ->call('create');

    $created = Admin::where('email', 'root@test.com')->first();

    expect($created)->not->toBeNull()
        ->and($created->firstname)->toBe('Super')
        ->and((int) $created->role)->toBe(1)
        ->and((int) $created->status)->toBe(1)
        ->and($created->image)->toBe('')
        ->and($created->date_register)->not->toBeNull()
        ->and(Hash::check('secret123', $created->password))->toBeTrue();
});

test('creating an admin with a duplicate email fails validation', function () {
    Livewire::test(CreateAdminUser::class)
        ->fillForm([
            'firstname' => 'Dupe',
            'lastname' => 'Admin',
            'email' => 'admin@test.com',
            'password' => 'secret123',
            'role' => 1,
            'status' => 1,
        ])
        ->call('create')
        ->assertHasErrors(['data.email' => 'unique']);
});

test('editing an admin with a blank password keeps the existing password', function () {
    Livewire::test(EditAdminUser::class, ['record' => $this->admin->getKey()])
        ->fillForm([
            'firstname' => 'Renamed',
            'lastname' => 'Admin',
            'email' => 'admin@test.com',
            'password' => '',
            'role' => 1,
            'status' => 1,
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->admin->refresh();

    expect($this->admin->firstname)->toBe('Renamed')
        ->and(Hash::check('password', $this->admin->password))->toBeTrue();
});

test('editing an admin with a new password updates the hash', function () {
    Livewire::test(EditAdminUser::class, ['record' => $this->admin->getKey()])
        ->fillForm([
            'firstname' => 'Test',
            'lastname' => 'Admin',
            'email' => 'admin@test.com',
            'password' => 'newpass123',
            'role' => 1,
            'status' => 0,
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->admin->refresh();

    expect(Hash::check('newpass123', $this->admin->password))->toBeTrue()
        ->and((int) $this->admin->status)->toBe(0);
});

// ─── Frontend users ──────────────────────────────────────────────────

test('users page renders and lists existing users', function () {
    UserGroup::create(['name' => 'Default']);

    User::create([
        'firstname' => 'Jane',
        'lastname' => 'Doe',
        'email' => 'jane@example.com',
        'password' => bcrypt('password'),
        'user_group_id' => 1,
        'date_added' => date('Y-m-d'),
    ]);

    $this->get('/admin/users')
        ->assertSuccessful()
        ->assertSee('jane@example.com');
});

test('admin can create a frontend user with a user group', function () {
    $group = UserGroup::create(['name' => 'Default']);

    Livewire::test(CreateUser::class)
        ->fillForm([
            'firstname' => 'John',
            'lastname' => 'Smith',
            'email' => 'john@example.com',
            'password' => 'secret123',
            'user_group_id' => $group->getKey(),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $user = User::where('email', 'john@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->firstname)->toBe('John')
        ->and((int) $user->user_group_id)->toBe((int) $group->getKey())
        ->and($user->date_added)->not->toBeNull()
        ->and(Hash::check('secret123', $user->password))->toBeTrue();
});

test('creating a frontend user with a duplicate email fails validation', function () {
    $group = UserGroup::create(['name' => 'Default']);

    User::create([
        'firstname' => 'Existing',
        'lastname' => 'User',
        'email' => 'dupe@example.com',
        'password' => bcrypt('password'),
        'user_group_id' => $group->getKey(),
        'date_added' => date('Y-m-d'),
    ]);

    Livewire::test(CreateUser::class)
        ->fillForm([
            'firstname' => 'Dupe',
            'lastname' => 'User',
            'email' => 'dupe@example.com',
            'password' => 'secret123',
            'user_group_id' => $group->getKey(),
        ])
        ->call('create')
        ->assertHasErrors(['data.email' => 'unique']);
});

test('editing a frontend user with a blank password keeps the existing password', function () {
    $group = UserGroup::create(['name' => 'Default']);

    $user = User::create([
        'firstname' => 'Kate',
        'lastname' => 'Doe',
        'email' => 'kate@example.com',
        'password' => bcrypt('password'),
        'user_group_id' => $group->getKey(),
        'date_added' => date('Y-m-d'),
    ]);

    Livewire::test(EditUser::class, ['record' => $user->getKey()])
        ->fillForm([
            'firstname' => 'Katherine',
            'lastname' => 'Doe',
            'email' => 'kate@example.com',
            'password' => '',
            'user_group_id' => $group->getKey(),
        ])
        ->call('save')
        ->assertHasNoErrors();

    $user->refresh();

    expect($user->firstname)->toBe('Katherine')
        ->and(Hash::check('password', $user->password))->toBeTrue();
});

// ─── User groups ─────────────────────────────────────────────────────

test('user groups page renders and lists groups', function () {
    UserGroup::create(['name' => 'Default']);

    $this->get('/admin/user-groups')
        ->assertSuccessful()
        ->assertSee('Default');
});

test('admin can create a user group', function () {
    Livewire::test(CreateUserGroup::class)
        ->fillForm(['name' => 'Editors'])
        ->call('create')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('user_group', ['name' => 'Editors']);
});

test('admin can delete a user group', function () {
    $group = UserGroup::create(['name' => 'Temp']);

    Livewire::test(ListUserGroups::class)
        ->callTableAction('delete', $group);

    $this->assertDatabaseMissing('user_group', ['user_group_id' => $group->user_group_id]);
});

test('admin can delete an admin account', function () {
    $other = Admin::create([
        'firstname' => 'Fire',
        'lastname' => 'Me',
        'email' => 'fireme@test.com',
        'password' => bcrypt('password'),
        'status' => 1,
        'role' => 1,
        'image' => '',
        'last_login' => now(),
        'date_register' => now(),
    ]);

    Livewire::test(ListAdminUsers::class)
        ->callTableAction('delete', $other);

    $this->assertDatabaseMissing('admin', ['admin_id' => $other->admin_id]);
});

test('admin can delete a frontend user', function () {
    $group = UserGroup::create(['name' => 'Default']);

    $user = User::create([
        'firstname' => 'Gone',
        'lastname' => 'Soon',
        'email' => 'gone@example.com',
        'password' => bcrypt('password'),
        'user_group_id' => $group->getKey(),
        'date_added' => date('Y-m-d'),
    ]);

    Livewire::test(ListUsers::class)
        ->callTableAction('delete', $user);

    $this->assertDatabaseMissing('user', ['user_id' => $user->user_id]);
});

test('admin can edit a user group', function () {
    $group = UserGroup::create(['name' => 'Old Name']);

    Livewire::test(EditUserGroup::class, ['record' => $group->getKey()])
        ->fillForm(['name' => 'New Name'])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('user_group', [
        'user_group_id' => $group->getKey(),
        'name' => 'New Name',
    ]);
});
