<?php

namespace App\Services\Contracts;

use App\Dto\LoginDto;
use Illuminate\Validation\ValidationException;

interface AuthServiceContract extends GenericServiceContract
{
    /**
     * @throws ValidationException
     */
    public function login(LoginDto $loginDto);
}
