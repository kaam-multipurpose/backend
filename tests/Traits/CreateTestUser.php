<?php

namespace Tests\Traits;

use App\Enum\UserRolesEnum;
use App\Models\User;

trait CreateTestUser
{
    public function createUser(UserRolesEnum $userRoles, array $attributes): User
    {
        $user = User::factory()->create($attributes);
        $user->assignRole($userRoles->value);

        return $user;
    }
}
