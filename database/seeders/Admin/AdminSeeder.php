<?php

namespace Database\Seeders\Admin;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin role
        Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'admin',
        ]);

        // Create Super Admin user
        $admin = Admin::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Test TopAdmin',
                'password' => Hash::make('12345678'),
            ]
        );

        // Assign role
        $admin->assignRole('Super Admin');

        // Create random admins
        Admin::factory()->count(5)->create();
    }
}
