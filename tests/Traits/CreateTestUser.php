<?php

declare(strict_types=1);

namespace Tests\Traits;

use App\Enums\UserRolesEnum;
use App\Models\User;

trait CreateTestUser
{
    public function createUser(UserRolesEnum $userRole, array $attributes = []): User
    {
        $user = User::factory()->create($attributes);
        $user->assignRole($userRole->value);

        return $user;
    }
}
