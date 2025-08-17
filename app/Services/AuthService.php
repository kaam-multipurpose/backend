<?php

namespace App\Services;

use App\Dto\LoginDto;
use App\Dto\LoginServiceResponseDto;
use App\Models\RefreshToken;
use App\Models\User;
use App\Services\Contracts\AuthServiceContract;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthService implements AuthServiceContract
{
    use HasAuthenticatedUser, HasLogger;

    public function __construct()
    {
    }

    /**
     * @param LoginDto $loginDto
     * @return LoginServiceResponseDto
     */
    public function login(LoginDto $loginDto): LoginServiceResponseDto
    {
        self::logInfo("Attempt to login", [
            'email' => $loginDto->email,
        ]);

        if (!Auth::Attempt($loginDto->toArray())) {

            self::logWarning('Login attempt failed', [
                "email" => $loginDto->email,
            ]);

            throw ValidationException::withMessages([
                'global' => ['The provided credentials are incorrect.'],
            ]);
        }

        try {
            $user = Auth::user();

            $user->tokens()->delete();
            $user->refreshToken()->delete();

            return $this->generateTokens($user);

        } catch (\Throwable $e) {
            self::logException($e, "Unable to login", [
                "email" => $loginDto->email,
            ]);
            throw ValidationException::withMessages([
                'global' => ['Unable to login'],
            ]);
        }
    }

    /**
     * @param string $refreshToken
     * @return LoginServiceResponseDto
     * @throws AuthenticationException
     */
    public function refreshToken(string $refreshToken): LoginServiceResponseDto
    {
        try {
            self::logInfo("Attempt to refresh token");

            $refreshRecord = RefreshToken::where("token", $refreshToken)
                ->where('expires_at', '>', now())
                ->with('user')
                ->first();

            if (!$refreshRecord || !$refreshRecord->user) {
                throw new AccessDeniedHttpException("Invalid refresh token");
            }

            $user = $refreshRecord->user;
            $user->tokens()->delete();
            $user->refreshToken()->delete();

            return $this->generateTokens($user);

        } catch (\Throwable $e) {
            self::logException($e, "Unable to refresh token");
            throw ValidationException::withMessages([
                "global" => ["Unable to refresh token"]
            ]);
        }
    }

    /**
     * @param Authenticatable|User $user
     * @return LoginServiceResponseDto
     */
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

        return new LoginServiceResponseDto()
            ->withUser($user)
            ->withToken($token, $expiresAt)
            ->withRefreshToken($refreshToken, $refreshTokenExpiresAt);
    }

    /**
     * @return array
     */
    public function logout(): array
    {

        try {
            self::logInfo("Attempt to logout");

            $user = self::getLoggedInUser();
            $user->tokens()->delete();
            $user->refreshToken()->delete();
            return [
                "email" => $user->email,
            ];
        } catch (\Throwable $e) {
            self::logException($e, "Unable to logout");
            throw ValidationException::withMessages([
                "global" => ["unable to logout"]
            ]);
        }
    }
}
