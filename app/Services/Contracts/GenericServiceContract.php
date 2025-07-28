<?php

namespace App\Services\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;

interface GenericServiceContract
{
    public function getLoggedInUser(): ?Authenticatable;

    public function setLoggedInUser(Authenticatable $user): void;
}
