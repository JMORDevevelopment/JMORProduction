<?php

use App\Filament\Admin\Resources\UserResource\Pages\CreateUser;
use App\Models\Admin;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);
});

function createRbacMember(array $overrides = []): User
{
    $user = User::create(array_merge([
        'firstname' => 'Member',
        'lastname' => 'Test',
        'email' => 'rbac.member@example.com',
        'password' => bcrypt('secret-password'),
        'date_added' => now()->toDateString(),
    ], $overrides));

    $user->syncRoles([ensureMemberRole()]);

    return $user;
}

test('the default member role can access every dashboard page and checkout', function () {
    $user = createRbacMember();

    $this->actingAs($user);

    $this->get(route('dashboard'))->assertSuccessful();
    $this->get(route('dashboard.orders'))->assertSuccessful();
    $this->get(route('dashboard.user_settings'))->assertSuccessful();
    $this->get(route('dashboard.giftcard'))->assertSuccessful();
    $this->get(route('dashboard.testimonial'))->assertSuccessful();

    // Empty cart redirects home; a permission denial would be a 403
    // before the controller ever runs.
    $this->get(route('checkout'))->assertRedirect(route('home'));
});

test('a restricted role only reaches the pages it was granted', function () {
    Permission::firstOrCreate(['name' => 'viewDashboard', 'guard_name' => 'web']);

    $restricted = Role::create(['name' => 'dashboard_only', 'guard_name' => 'web']);
    $restricted->givePermissionTo('viewDashboard');

    $user = createRbacMember(['email' => 'restricted@example.com']);
    $user->syncRoles([$restricted]);

    $this->actingAs($user);

    $this->get(route('dashboard'))->assertSuccessful();

    $this->get(route('dashboard.orders'))->assertForbidden();
    $this->get(route('dashboard.user_settings'))->assertForbidden();
    $this->get(route('dashboard.giftcard'))->assertForbidden();
    $this->get(route('dashboard.testimonial'))->assertForbidden();
    $this->get(route('checkout'))->assertForbidden();
});

test('a user without any role is denied the whole dashboard', function () {
    $user = User::create([
        'firstname' => 'No',
        'lastname' => 'Role',
        'email' => 'norole@example.com',
        'password' => bcrypt('secret-password'),
        'date_added' => now()->toDateString(),
    ]);

    $this->actingAs($user);

    $this->get(route('dashboard'))->assertForbidden();
    $this->get(route('dashboard.orders'))->assertForbidden();
    $this->get(route('checkout'))->assertForbidden();
});

test('registration assigns the member role automatically', function () {
    ensureMemberRole();

    $this->post('/sign-up/validate', [
        'firstname' => 'New',
        'lastname' => 'Member',
        'email' => 'brand.new@example.com',
        'password' => 'secret-password',
        'address' => '123 Main Street',
        'city' => 'Springfield',
        'state' => 'IL',
        'zip' => '62704',
    ])->assertRedirect(route('checkout'));

    $user = User::where('email', 'brand.new@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->hasRole('member'))->toBeTrue();
});

test('admin can create a frontend user with a member role', function () {
    ensureMemberRole();
    Role::create(['name' => 'vip_member', 'guard_name' => 'web']);
    $group = UserGroup::create(['name' => 'Default']);

    $super = Admin::create([
        'firstname' => 'Super',
        'lastname' => 'Admin',
        'email' => 'rbac.userres@test.com',
        'password' => bcrypt('password'),
        'status' => 1,
        'role' => 1,
        'image' => '',
        'last_login' => now(),
        'date_register' => now(),
    ]);

    $this->actingAs($super, 'admin');

    Livewire::test(CreateUser::class)
        ->fillForm([
            'firstname' => 'Form',
            'lastname' => 'Created',
            'email' => 'form.created@example.com',
            'password' => 'secret123',
            'user_group_id' => $group->getKey(),
            'role' => 'vip_member',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $created = User::where('email', 'form.created@example.com')->first();

    expect($created)->not->toBeNull()
        ->and($created->hasRole('vip_member'))->toBeTrue()
        ->and($created->hasRole('member'))->toBeFalse();
});

test('member roles screen is accessible for super admins', function () {
    ensureMemberRole();

    $super = Admin::create([
        'firstname' => 'Super',
        'lastname' => 'Admin',
        'email' => 'rbac.memberroles@test.com',
        'password' => bcrypt('password'),
        'status' => 1,
        'role' => 1,
        'image' => '',
        'last_login' => now(),
        'date_register' => now(),
    ]);

    $this->actingAs($super, 'admin');

    $this->get('/admin/member-roles')->assertSuccessful();
});

test('member roles screen is forbidden for non-super admins', function () {
    $limited = Admin::create([
        'firstname' => 'Limited',
        'lastname' => 'Admin',
        'email' => 'rbac.limited@test.com',
        'password' => bcrypt('password'),
        'status' => 1,
        'role' => 2,
        'image' => '',
        'last_login' => now(),
        'date_register' => now(),
    ]);

    $this->actingAs($limited, 'admin');

    $this->get('/admin/member-roles')->assertForbidden();
});
