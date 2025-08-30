<?php

namespace App\Http\Controllers\Auth;

use App\Dto\LoginDto;
use App\Dto\LoginServiceResponseDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\Contracts\AuthServiceContract;
use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Cookie as SymPyCookie;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthController extends Controller
{
    use HasAuthenticatedUser, HasLogger;

    public function __construct(protected AuthServiceContract $authService) {}

    /**
     * @throws AuthenticationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $loginDto = LoginDto::fromValidated($request->validated());

        $response = $this->authService->login($loginDto);

        $data = [
            'user' => new UserResource($response->user),
            'expires_at' => $response->expiresAt,
        ];

        self::logInfo('Login Successful', [
            'email' => $data['user']['email'],
            'user_id' => $data['user']['id'],
            'role' => $response->user->roles->first()?->name,
        ]);

        return ApiResponse::success($data, 'Logged in successfully.')
            ->withHeaders($this->withAuthHeader($response->token))
            ->withCookie($this->rotateRefreshToken($response));
    }

    /**
     * @throws AuthenticationException
     */
    public function refreshToken(Request $request): JsonResponse
    {
        $refreshToken = $request->cookie('refresh_token');

        if (! $refreshToken) {
            throw new NotFoundHttpException('Refresh token not found');
        }

        $response = $this->authService->refreshToken($refreshToken);

        self::setLoggedInUser($response->user);
        self::logInfo('Token Refreshed Successfully');

        return ApiResponse::success([
            'expires_at' => $response->expiresAt,
        ])->withHeaders($this->withAuthHeader($response->token))
            ->withCookie($this->rotateRefreshToken($response));
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        self::logInfo('Logged out successfully');

        return ApiResponse::success(message: 'Logged out successfully.')
            ->withCookie(Cookie::forget('refresh_token'));
    }

    private function withAuthHeader(string $token): array
    {
        return [
            'Authorization' => $token,
            'Access-Control-Expose-Headers' => 'Authorization',
        ];
    }

    private function rotateRefreshToken(LoginServiceResponseDto $dto): SymPyCookie
    {
        return Cookie::make(
            'refresh_token',
            value: $dto->refreshToken,
            minutes: (int) now()->diffInMinutes($dto->refreshTokenExpiresAt),
        );
    }
}
