<?php

namespace App\Utils\Trait;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

trait HasAuthenticatedUser
{
    protected ?Authenticatable $loggedInUser;

    public function getLoggedInUser(): ?Authenticatable
    {
        return $this->loggedInUser ?? Auth::user();
    }

    public function setLoggedInUser(Authenticatable $user): void
    {
        $this->loggedInUser = $user;
    }
}
