<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

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
        if ($env !== 'testing') {
            $seeder[] = SuperAdminSeeder::class;
        }

        $level = match ($env) {
            "local", "testing" => [
                UserSeeder::class,
                CategorySeeder::class,
            ],
            default => []
        };
        $this->call(array_merge($seeder, $level));
    }
}
