<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create the 'admin' role
        $adminRole = Role::create(['name' => 'admin']);

        // Create permissions
        $permissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            // Add more permissions as needed
        ];

        // Assign permissions to the 'admin' role
        foreach ($permissions as $permission) {
            $permission = Permission::create(['name' => $permission]);
            $adminRole->givePermissionTo($permission);
        }
    }
}
