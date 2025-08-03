<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $env = config("app.env");

        $seeder = match ($env) {
            "local" => [
                UserSeeder::class,
                ProductSeeder::class,
                RoleAndPermissionSeeder::class,
                SuperAdminSeeder::class
            ],
            "testing" => [
                UserSeeder::class,
                ProductSeeder::class,
                RoleAndPermissionSeeder::class,
            ],
            default => []
        };
        $this->call($seeder);
    }
}
