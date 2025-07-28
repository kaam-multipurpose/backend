<?php

namespace Database\Seeders;

use App\Enum\UserRolesEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = 0;
        User::factory(2)->create()->each(function ($user) use (&$count) {
            $roles = [
                UserRolesEnum::ADMIN->value,
                UserRolesEnum::SALES_REP->value,
            ];
            $user->syncRoles($roles[$count]);
            ++$count;
        });
    }
}
