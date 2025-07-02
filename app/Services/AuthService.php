<?php

namespace App\Services;

use App\Dto\LoginDto;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Services\Contracts\AuthServiceContract;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class AuthService implements AuthServiceContract
{
    /**
     * @param UserRepositoryContract $userRepository
     */
    public function __construct(protected UserRepositoryContract $userRepository)
    {}

    /**
     * @param LoginDto $loginDto
     * @return array
     * @throws ValidationException
     */
    public function login(LoginDto $loginDto): array
    {
        $user = $this->userRepository->findByEmail($loginDto->email);

        if (!$user || ! Hash::check($loginDto->password, $user->password)) {
            throw ValidationException::withMessages([
                'global' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken("auth-token")->plainTextToken;

        return [
            "token" => $token,
            "user" => $user
        ];
    }
}
