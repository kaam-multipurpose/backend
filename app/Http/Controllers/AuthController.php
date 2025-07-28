<?php

namespace App\Http\Controllers;

use App\Dto\LoginDto;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Utils\Logger\Contract\LoggerContract;
use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use HasAuthenticatedUser, HasLogger;

    public function __construct(protected AuthService $authService, protected LoggerContract $logger)
    {
    }

    /**
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $loginDto = LoginDto::fromValidated($request->validated());
        $response = $this->authService->login($loginDto);

        $message = 'Logged in successfully.';
        $data = [
            'token' => $response['token'],
            'user' => new UserResource($response['user']),
        ];

        $this->log("info", "Login Successful", [
            "email" => $data["user"]->email,
            "user_id" => $data["user"]->id,
            "role" => optional($response['user']->roles->first())->name,
        ]);

        return ApiResponse::success($data, $message);
    }
}
