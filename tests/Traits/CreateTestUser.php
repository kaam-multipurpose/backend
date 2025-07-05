<?php

namespace Tests\Traits;

use App\Enum\UserRolesEnum;
use App\Models\User;

trait CreateTestUser
{
    public function createUser(UserRolesEnum $userRole, array $attributes): User
    {
        $user = User::factory()->create($attributes);
        $user->assignRole($userRole->value);

        return $user;
    }
}
