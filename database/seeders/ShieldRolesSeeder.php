<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ShieldRolesSeeder extends Seeder
{
    /**
     * Ensure the super_admin role exists, holds every permission, and is
     * assigned to all existing super admins (role column = 1).
     */
    public function run(): void
    {
        $guard = 'admin';

        $superAdmin = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => $guard,
        ]);

        $superAdmin->givePermissionTo(Permission::where('guard_name', $guard)->get());

        Admin::where('role', 1)
            ->get()
            ->each(fn (Admin $admin) => $admin->syncRoles([$superAdmin]));
    }
}
