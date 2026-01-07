<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Dtos\LoginDto;
use App\Dtos\LoginServiceResponseDto;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

interface AuthServiceContract
{
    public function login(LoginDto $loginDto): LoginServiceResponseDto;

    public function refreshToken(string $refreshToken): LoginServiceResponseDto;

    public function logout(): array;
}
