<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FrontendRbacSeeder extends Seeder
{
    /**
     * Website-member (web guard) permissions and the default "member"
     * role. Every existing user is given the member role so current
     * dashboard access is preserved; the super admin can then create
     * narrower roles in the panel.
     */
    public function run(): void
    {
        $guard = 'web';

        $permissions = [
            'viewDashboard' => 'View dashboard home',
            'viewOrders' => 'View orders and order invoices',
            'updateProfile' => 'Update own profile settings',
            'viewGiftCards' => 'View gift card history',
            'createTestimonials' => 'Submit testimonials',
            'accessCheckout' => 'Access checkout',
        ];

        foreach ($permissions as $name => $label) {
            Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => $guard,
            ]);
        }

        $member = Role::firstOrCreate([
            'name' => 'member',
            'guard_name' => $guard,
        ]);

        $member->givePermissionTo(
            Permission::where('guard_name', $guard)->get()
        );

        User::query()
            ->whereDoesntHave('roles')
            ->get()
            ->each(fn (User $user) => $user->syncRoles([$member]));
    }
}
