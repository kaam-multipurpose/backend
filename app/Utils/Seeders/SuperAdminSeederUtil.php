<?php

namespace App\Utils\Seeders;

use App\Enum\UserRolesEnum;
use App\Models\User;
use App\Utils\Logger\Dto\LoggerContextDto;
use App\Utils\Logger\Logger;

class SuperAdminSeederUtil
{
    public static function run(): void
    {
        self::create();
    }

    public static function create(): void
    {
        try {
            $createUser = User::firstOrCreate(
                ['email' => config('app.super_admin.email')],
                [
                    'first_name' => config('app.super_admin.first_name'),
                    'last_name' => config('app.super_admin.last_name'),
                    'phone_number' => config('app.super_admin.phone_number'),
                    'email_verified_at' => now(),
                    'password' => config('app.default_user_password'),
                ]
            );
            $createUser->syncRoles(UserRolesEnum::SUPER_ADMIN->value);
        } catch (\Throwable $e) {
            Logger::error($e->getMessage(), LoggerContextDto::fromException($e, extra: [
                'info' => 'When seeding super admin',
            ]));
        }
    }
}
