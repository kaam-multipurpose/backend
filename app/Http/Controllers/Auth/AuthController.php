<?php

namespace App\Http\Controllers\Auth;

use App\Dto\LoginDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\Contracts\AuthServiceContract;
use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use HasAuthenticatedUser, HasLogger;

    public function __construct(protected AuthServiceContract $authService)
    {
    }

    /**
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $loginDto = LoginDto::fromValidated($request->validated());
        $response = $this->authService->login($loginDto);

        $data = [
            'token' => $response['token'],
            'user' => new UserResource($response['user']),
        ];

        $this->logInfo("Login Successful", [
            "email" => $data["user"]->email,
            "user_id" => $data["user"]->id,
            "role" => $response['user']->roles->first()?->name,
        ]);

        return ApiResponse::success($data, 'Logged in successfully.');
    }
}
