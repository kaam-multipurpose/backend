<?php

namespace App\Services;

use App\Dto\LoginDto;
use App\Models\User;
use App\Services\Contracts\AuthServiceContract;
use App\Utils\Logger\Contract\LoggerContract;
use App\Utils\Logger\Dto\LoggerContextDto;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService implements AuthServiceContract
{
    use HasAuthenticatedUser, HasLogger;

    public function __construct(
        protected LoggerContract $logger,
    )
    {
    }

    /**
     * @throws ValidationException
     */
    public function login(LoginDto $loginDto): array
    {
        $this->log("info", "Attempt to login", [
            'email' => $loginDto->email,
        ]);

        $user = User::where('email', $loginDto->email)->with('roles.permissions')->first();

        if (!$user || !Hash::check($loginDto->password, $user->password)) {

            $this->logger->warning('Login attempt failed', new LoggerContextDto(
                email: $loginDto->email,
            ));

            throw ValidationException::withMessages([
                'global' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'token' => $token,
            'user' => $user,
        ];
    }
}
