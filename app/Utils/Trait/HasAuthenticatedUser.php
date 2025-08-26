<?php

namespace App\Utils\Trait;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

trait HasAuthenticatedUser
{
    private static ?Authenticatable $loggedInUser;

    public static function getLoggedInUser(): ?Authenticatable
    {
        return self::$loggedInUser ?? Auth::user();
    }

    public static function setLoggedInUser(Authenticatable $user): void
    {
        self::$loggedInUser = $user;
    }
}
