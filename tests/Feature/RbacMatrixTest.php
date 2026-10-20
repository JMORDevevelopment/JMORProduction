<?php

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);
});

function rbacMatrixSuper(string $email): Admin
{
    return Admin::create([
        'firstname' => 'Super',
        'lastname' => 'Admin',
        'email' => $email,
        'password' => bcrypt('password'),
        'status' => 1,
        'role' => 1,
        'image' => '',
        'last_login' => now(),
        'date_register' => now(),
    ]);
}

function rbacMatrixLimited(string $email): Admin
{
    return Admin::create([
        'firstname' => 'Limited',
        'lastname' => 'Admin',
        'email' => $email,
        'password' => bcrypt('password'),
        'status' => 1,
        'role' => 2,
        'image' => '',
        'last_login' => now(),
        'date_register' => now(),
    ]);
}

/**
 * Every admin-panel resource that has a policy (policies are what enforce
 * permissions). GiftCardTransaction is excluded from Shield on purpose.
 *
 * @return array<class-string, array{slug: string, permission: string}>
 */
function rbacMatrixAdminResources(): array
{
    $base = dirname(__DIR__, 2).'/app/Filament/Admin/Resources/';

    $files = array_merge(
        glob($base.'*Resource.php'),
        glob($base.'Roles/*Resource.php'),
    );

    $map = [];

    foreach ($files as $file) {
        $relative = str_replace($base, '', $file);
        $class = 'App\\Filament\\Admin\\Resources\\'.str_replace(['/', '.php'], ['\\', ''], $relative);

        if (! class_exists($class)) {
            continue;
        }

        $model = $class::getModel();

        $map[$class] = [
            'slug' => $class::getSlug(),
            'permission' => 'ViewAny:'.class_basename($model),
            'model' => $model,
        ];
    }

    return $map;
}

function rbacMatrixWebUser(string $email): User
{
    return User::create([
        'firstname' => 'Web',
        'lastname' => 'Member',
        'email' => $email,
        'password' => bcrypt('secret-password'),
        'date_added' => now()->toDateString(),
    ]);
}

/**
 * Static copy of every admin resource slug => its ViewAny permission.
 *
 * Data providers run before the app boots, and Filament's getSlug() needs a
 * booted app, so the list is duplicated here. Each matrix test cross-checks
 * it against the real resources at runtime so it cannot silently drift.
 *
 * @return array<string, string>
 */
function rbacMatrixSlugPermissions(): array
{
    return [
        'admin-users' => 'ViewAny:Admin',
        'blogs' => 'ViewAny:Blog',
        'brand-guidelines' => 'ViewAny:BrandGuideline',
        'case-studies' => 'ViewAny:CaseStudy',
        'category-radio-shows' => 'ViewAny:CategoryRadioShow',
        'categories' => 'ViewAny:Category',
        'checkout-forms' => 'ViewAny:CheckoutMeta',
        'contact-us' => 'ViewAny:ContactUs',
        'events' => 'ViewAny:Event',
        'gift-cards' => 'ViewAny:GiftCard',
        'gift-card-transactions' => 'ViewAny:Transaction',
        'home-tabs' => 'ViewAny:HomeTab',
        'languages' => 'ViewAny:Language',
        'media-inquiries' => 'ViewAny:MediaInquiry',
        'media-resources' => 'ViewAny:MediaResource',
        'media-videos' => 'ViewAny:MediaVideo',
        'member-roles' => 'ViewAny:Role',
        'menu-groups' => 'ViewAny:MenuGroup',
        'menus' => 'ViewAny:Menu',
        'news' => 'ViewAny:News',
        'packages' => 'ViewAny:Package',
        'pages' => 'ViewAny:Page',
        'press-releases' => 'ViewAny:PressRelease',
        'radio-shows' => 'ViewAny:RadioShow',
        'random-acts' => 'ViewAny:RandomActsOfKindness',
        'recommended' => 'ViewAny:Recommended',
        'request-information' => 'ViewAny:RequestInformation',
        'services' => 'ViewAny:Service',
        'sliders' => 'ViewAny:Slider',
        'testimonials' => 'ViewAny:Testimonial',
        'transactions' => 'ViewAny:Transaction',
        'user-groups' => 'ViewAny:UserGroup',
        'users' => 'ViewAny:User',
        'shield/roles' => 'ViewAny:Role',
    ];
}

function rbacMatrixAssertDatasetMatchesRealResources(): void
{
    $real = [];

    foreach (rbacMatrixAdminResources() as $meta) {
        $real[$meta['slug']] = $meta['permission'];
    }

    ksort($real);
    $expected = rbacMatrixSlugPermissions();
    ksort($expected);

    expect($real)->toEqual($expected);
}

test('admin resource {slug} is closed without its permission and opens with only that permission', function (string $slug, string $permission) {
    rbacMatrixAssertDatasetMatchesRealResources();

    $model = collect(rbacMatrixAdminResources())->firstWhere('slug', $slug)['model'] ?? null;

    if ($model === null || Gate::getPolicyFor($model) === null) {
        $this->markTestSkipped("{$slug} has no policy (excluded from Shield).");
    }

    Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin']);

    $role = Role::create(['name' => 'matrix_'.$slug, 'guard_name' => 'admin']);
    $admin = rbacMatrixLimited('mb'.substr(md5($slug), 0, 8).'@t.com');

    $admin->assignRole($role);
    $this->actingAs($admin, 'admin');
    $this->get("/admin/{$slug}")->assertForbidden();

    $role->givePermissionTo($permission);
    $this->get("/admin/{$slug}")->assertSuccessful();
})->with(array_map(
    fn (string $permission, string $slug): array => [$slug, $permission],
    rbacMatrixSlugPermissions(),
    array_keys(rbacMatrixSlugPermissions()),
));

test('admin permission {permission} never opens a different resource page', function (string $slug, string $permission) {
    rbacMatrixAssertDatasetMatchesRealResources();

    Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin']);

    $role = Role::create(['name' => 'leak_'.$slug, 'guard_name' => 'admin']);
    $role->givePermissionTo($permission);

    $admin = rbacMatrixLimited('ml'.substr(md5($slug), 0, 8).'@t.com');
    $admin->assignRole($role);

    $this->actingAs($admin, 'admin');

    foreach (rbacMatrixAdminResources() as $other) {
        if ($other['slug'] === $slug || $other['permission'] === $permission) {
            continue;
        }

        $this->get("/admin/{$other['slug']}")->assertForbidden();
    }
})->with(array_map(
    fn (string $permission, string $slug): array => [$slug, $permission],
    rbacMatrixSlugPermissions(),
    array_keys(rbacMatrixSlugPermissions()),
));

test('a super admin can open every resource page', function () {
    $super = rbacMatrixSuper('matrix.super@test.com');
    $this->actingAs($super, 'admin');

    $closed = [];

    foreach (rbacMatrixAdminResources() as $meta) {
        if ($this->get("/admin/{$meta['slug']}")->status() !== 200) {
            $closed[] = $meta['slug'];
        }
    }

    expect($closed)->toBeEmpty();
});

test('granting and revoking a permission changes access on the very next request without re-login', function () {
    Permission::firstOrCreate(['name' => 'ViewAny:Blog', 'guard_name' => 'admin']);

    $role = Role::create(['name' => 'dynamic_admin', 'guard_name' => 'admin']);
    $admin = rbacMatrixLimited('matrix.dynamic@test.com');
    $admin->assignRole($role);

    $this->actingAs($admin, 'admin');

    $this->get('/admin/blogs')->assertForbidden();

    $role->givePermissionTo('ViewAny:Blog');
    $this->get('/admin/blogs')->assertSuccessful();

    $role->revokePermissionTo('ViewAny:Blog');
    $this->get('/admin/blogs')->assertForbidden();

    $role->givePermissionTo('ViewAny:Blog');
    $this->get('/admin/blogs')->assertSuccessful();
});

test('editing a shared role updates access for every admin using it at once', function () {
    Permission::firstOrCreate(['name' => 'ViewAny:Event', 'guard_name' => 'admin']);

    $role = Role::create(['name' => 'shared_events', 'guard_name' => 'admin']);
    $one = rbacMatrixLimited('matrix.shared1@test.com');
    $two = rbacMatrixLimited('matrix.shared2@test.com');
    $one->assignRole($role);
    $two->assignRole($role);

    $this->actingAs($one, 'admin');
    $this->get('/admin/events')->assertForbidden();

    $role->givePermissionTo('ViewAny:Event');

    $this->actingAs($one, 'admin');
    $this->get('/admin/events')->assertSuccessful();
});

test('a shared role edit also reaches the other admins using that same role', function () {
    Permission::firstOrCreate(['name' => 'ViewAny:Event', 'guard_name' => 'admin']);

    $role = Role::create(['name' => 'shared_events_two', 'guard_name' => 'admin']);
    $role->givePermissionTo('ViewAny:Event');

    $one = rbacMatrixLimited('matrix.shared3@test.com');
    $two = rbacMatrixLimited('matrix.shared4@test.com');
    $one->assignRole($role);
    $two->assignRole($role);

    $this->actingAs($two, 'admin');
    $this->get('/admin/events')->assertSuccessful();
});

test('moving an admin to a different role immediately swaps their access', function () {
    Permission::firstOrCreate(['name' => 'ViewAny:News', 'guard_name' => 'admin']);

    $newsRole = Role::create(['name' => 'news_team', 'guard_name' => 'admin']);
    $newsRole->givePermissionTo('ViewAny:News');

    $emptyRole = Role::create(['name' => 'no_access_team', 'guard_name' => 'admin']);

    $admin = rbacMatrixLimited('matrix.swap@test.com');
    $admin->assignRole($newsRole);

    $this->actingAs($admin, 'admin');
    $this->get('/admin/news')->assertSuccessful();

    $admin->syncRoles([$emptyRole]);
    $this->get('/admin/news')->assertForbidden();

    $admin->syncRoles([$newsRole]);
    $this->get('/admin/news')->assertSuccessful();
});

test('an admin with several roles gets the combined permissions of all of them', function () {
    Permission::firstOrCreate(['name' => 'ViewAny:Slider', 'guard_name' => 'admin']);
    Permission::firstOrCreate(['name' => 'ViewAny:Package', 'guard_name' => 'admin']);

    $sliders = Role::create(['name' => 'only_sliders', 'guard_name' => 'admin']);
    $sliders->givePermissionTo('ViewAny:Slider');
    $packages = Role::create(['name' => 'only_packages', 'guard_name' => 'admin']);
    $packages->givePermissionTo('ViewAny:Package');

    $admin = rbacMatrixLimited('matrix.combo@test.com');
    $admin->assignRole([$sliders, $packages]);

    $this->actingAs($admin, 'admin');
    $this->get('/admin/sliders')->assertSuccessful();
    $this->get('/admin/packages')->assertSuccessful();
    $this->get('/admin/events')->assertForbidden();
});

test('every website permission alone opens exactly its own pages and nothing else', function () {
    $permissionRoutes = [
        'viewDashboard' => '/dashboard',
        'viewOrders' => '/dashboard/orders',
        'updateProfile' => '/dashboard/user_settings',
        'viewGiftCards' => '/dashboard/giftcard',
        'createTestimonials' => '/dashboard/testimonial',
        'accessCheckout' => '/checkout',
    ];

    expect($permissionRoutes)->toHaveCount(6);

    $failures = [];

    $index = 0;
    foreach ($permissionRoutes as $ownPermission => $ownRoute) {
        $role = Role::create(['name' => "web_matrix_{$index}", 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => $ownPermission, 'guard_name' => 'web']);
        $role->givePermissionTo($ownPermission);

        $user = rbacMatrixWebUser("matrix.web{$index}@example.com");
        $user->syncRoles([$role]);

        $this->actingAs($user);

        foreach ($permissionRoutes as $otherPermission => $otherRoute) {
            $response = $this->get($otherRoute);

            if ($otherPermission === $ownPermission) {
                // accessCheckout redirects home on an empty cart; anything
                // but 403 means the permission itself was accepted.
                if ($response->status() === 403) {
                    $failures[] = "{$ownPermission} could not open its own {$otherRoute}";
                }
            } elseif ($response->status() !== 403) {
                $failures[] = "{$ownPermission} also opened {$otherRoute} ({$otherPermission})";
            }
        }

        $index++;
    }

    expect($failures)->toBeEmpty();
});

test('revoking a website permission from the shared member role cuts access on the next request', function () {
    $member = ensureMemberRole();
    $user = rbacMatrixWebUser('matrix.dynamic.web@example.com');
    $user->syncRoles([$member]);

    $this->actingAs($user);
    $this->get('/dashboard/orders')->assertSuccessful();

    $member->revokePermissionTo('viewOrders');
    $this->get('/dashboard/orders')->assertForbidden();
    $this->get('/dashboard')->assertSuccessful();

    $member->givePermissionTo('viewOrders');
    $this->get('/dashboard/orders')->assertSuccessful();
});

test('a website user with several roles gets the combined permissions of all of them', function () {
    Permission::firstOrCreate(['name' => 'viewDashboard', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'viewOrders', 'guard_name' => 'web']);

    $one = Role::create(['name' => 'web_only_dashboard', 'guard_name' => 'web']);
    $one->givePermissionTo('viewDashboard');
    $two = Role::create(['name' => 'web_only_orders', 'guard_name' => 'web']);
    $two->givePermissionTo('viewOrders');

    $user = rbacMatrixWebUser('matrix.combo.web@example.com');
    $user->syncRoles([$one, $two]);

    $this->actingAs($user);
    $this->get('/dashboard')->assertSuccessful();
    $this->get('/dashboard/orders')->assertSuccessful();
    $this->get('/dashboard/giftcard')->assertForbidden();
});

test('removing every role from a website user locks them out of the whole member area', function () {
    $user = rbacMatrixWebUser('matrix.bare.web@example.com');
    $user->syncRoles([ensureMemberRole()]);

    $this->actingAs($user);
    $this->get('/dashboard')->assertSuccessful();

    $user->syncRoles([]);
    $this->get('/dashboard')->assertForbidden();
    $this->get('/dashboard/orders')->assertForbidden();
    $this->get('/dashboard/user_settings')->assertForbidden();
    $this->get('/dashboard/giftcard')->assertForbidden();
    $this->get('/dashboard/testimonial')->assertForbidden();
    $this->get('/checkout')->assertForbidden();
});
