<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create the admin user
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'), // Change this to a secure password
        ]);

        // Get or create the admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Assign the admin role to the user
        $user->assignRole($adminRole);
    }
}