<?php

namespace App\Http\Controllers;

use App\Dto\LoginDto;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Utils\Response\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    /**
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $loginDto = LoginDto::fromRequest($request);
        $loginService = $this->authService->login($loginDto);

        $message = 'Logged in successfully.';
        $data = [
            'token' => $loginService['token'],
            'user' => new UserResource($loginService['user']),
        ];

        return ApiResponse::success($data, $message);
    }
}
