<?php

namespace App\Utils\Seeders;

use App\Enum\UserRolesEnum;
use App\Models\User;

class SuperAdminSeederUtil
{
    public static function run(): void
    {
        self::create();
    }

    public static function create(): void
    {
        $createUser = User::firstOrCreate(
            ['email' => 'abdulazeemabdulazeez@gmail.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'phone_number' => '08105594926',
                'email_verified_at' => now(),
                'password' => config('app.default_user_password'),
            ]
        );
        $createUser->syncRoles(UserRolesEnum::SUPER_ADMIN->value);
    }
}
