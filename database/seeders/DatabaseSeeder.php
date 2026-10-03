<?php

namespace Database\Seeders;

use Database\Seeders\Admin\AdminSeeder;
use Database\Seeders\Admin\PermissionSeeder;
use Database\Seeders\Frontend\UserSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {

        $this->call(AdminSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CatalogSeeder::class);

    }

}
