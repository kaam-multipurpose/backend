<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enum\UserRolesEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->withRole(UserRolesEnum::ADMIN)->create();
        User::factory()->withRole(UserRolesEnum::SALES_REP)->create();
    }
}
