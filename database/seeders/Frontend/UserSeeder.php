<?php

namespace Database\Seeders\Frontend;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create specific test users with known credentials
        User::create([
            'name' => 'Test User',
            'email' => 'user@gmail.com',
            'password' => bcrypt('12345678'),
            'user_type' => 'user',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Vendor User',
            'email' => 'vendor@gmail.com',
            'password' => bcrypt('12345678'),
            'user_type' => 'vendor',
            'email_verified_at' => now(),
        ]);

        // Create random users using factory
        User::factory()->count(3)->create(); // 50 regular users
        User::factory()->vendor()->count(2)->create(); // 20 vendor users
    }
}
