<?php

namespace App\Http\Controllers;

use App\Dto\LoginDto;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService)
    {

    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $loginDto = LoginDto::fromRequest($request);

        $loginService  = $this->authService->login($loginDto);

        return Response::json([
            "success" => true,
            "message" => "Logged in successfully.",
            "data" => [
                "token" => $loginService["token"],
                "user" => new UserResource($loginService["user"]),
            ]
        ]);
    }
}
