<?php

namespace App\Services\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;

interface GenericServiceContract
{
    public static function getLoggedInUser(): ?Authenticatable;

    public static function setLoggedInUser(Authenticatable $user): void;
}
