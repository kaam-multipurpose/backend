<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Dtos\LoginDto;
use App\Dtos\LoginServiceResponseDto;
use App\Models\RefreshToken;
use App\Models\User;
use App\Services\AbstractService;
use App\Services\Contracts\AuthServiceContract;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

final class AuthService extends AbstractService implements AuthServiceContract
{
    public function login(LoginDto $loginDto): LoginServiceResponseDto
    {
        self::logInfo('Attempt to login', [
            'email' => $loginDto->email,
        ]);

        if (!Auth::Attempt($loginDto->toArray())) {

            self::logWarning('Login attempt failed', [
                'email' => $loginDto->email,
            ]);

            throw ValidationException::withMessages([
                'global' => ['The provided credentials are incorrect.'],
            ]);
        }

        return DB::transaction(function () {
            $user = Auth::user();

            $user->tokens()->delete();
            $user->refreshToken()->delete();

            return $this->generateTokens($user);
        });
    }
    
    public function refreshToken(string $refreshToken): LoginServiceResponseDto
    {

        self::logInfo('Attempt to refresh token');

        $refreshRecord = RefreshToken::where('token', $refreshToken)
            ->where('expires_at', '>', now())
            ->with('user')
            ->first();

        if (!$refreshRecord || !$refreshRecord->user) {
            throw new AccessDeniedHttpException('Invalid refresh token');
        }

        return DB::transaction(function () use ($refreshRecord) {
            /** @var User $user */
            $user = $refreshRecord->user;

            $user->tokens()->delete();
            $user->refreshToken()->delete();

            return $this->generateTokens($user);
        });
    }

    private function generateTokens(Authenticatable|User $user): LoginServiceResponseDto
    {
        $expiresAt = now()->addHours(2);
        $token = $user->createToken('token', expiresAt: $expiresAt)->plainTextToken;

        $refreshToken = Str::random(64);
        $refreshTokenExpiresAt = now()->addDays(7);

        $user->refreshToken()->create([
            'token' => $refreshToken,
            'expires_at' => $refreshTokenExpiresAt,
        ]);

        return (new LoginServiceResponseDto)
            ->withUser($user)
            ->withToken($token, $expiresAt)
            ->withRefreshToken($refreshToken, $refreshTokenExpiresAt);
    }

    public function logout(): array
    {

        self::logInfo('Attempt to logout');

        return DB::transaction(function () {
            /** @var User $user */
            $user = self::getLoggedInUser();

            $user->tokens()->delete();
            $user->refreshToken()->delete();

            return [
                'email' => $user->email,
            ];
        });
    }
}
