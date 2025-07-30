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

        $seeder = [
            RoleAndPermissionSeeder::class,
        ];

        $level = match ($env) {
            "local", "testing" => [
                UserSeeder::class,
                ProductSeeder::class,
            ],
            default => []
        };
        $this->call(array_merge(
            $seeder,
            $env != "testing" ? [SuperAdminSeeder::class] : [],
            $level
        ));
    }
}
