<?php

namespace App\Services\Contracts;

use App\Dto\LoginDto;
use App\Dto\LoginServiceResponseDto;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

interface AuthServiceContract
{
    /**
     * @param LoginDto $loginDto
     * @return LoginServiceResponseDto
     * @throws ValidationException
     * @throws AuthenticationException
     */
    public function login(LoginDto $loginDto): LoginServiceResponseDto;


    /**
     * @param string $refreshToken
     * @return LoginServiceResponseDto
     * @throws AuthenticationException
     */
    public function refreshToken(string $refreshToken): LoginServiceResponseDto;

    /**
     * @return array
     */
    public function logout(): array;
}
