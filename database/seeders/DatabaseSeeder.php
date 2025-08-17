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
                RoleAndPermissionSeeder::class,
                SuperAdminSeeder::class,
                UserSeeder::class,
            ],
            "testing" => [
                RoleAndPermissionSeeder::class,
            ],
            default => []
        };
        $this->call($seeder);
    }
}
