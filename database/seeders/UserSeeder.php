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
        $roles = [
            UserRolesEnum::ADMIN->value,
            UserRolesEnum::SALES_REP->value,
        ];
        User::factory(2)->create()->each(function ($user, $index) use ($roles) {
            $user->syncRoles($roles[$index]);
        });
    }
}
