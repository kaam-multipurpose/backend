<?php

namespace App\Exceptions\Handlers;

use App\Exceptions\CategoryServiceException;
use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasLogger;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthenticationExceptionHandler
{
    use HasLogger;

    public static function handle(AuthenticationException $exception): JsonResponse
    {
        self::logException($exception, "Authentication Failed");

        return ApiResponse::error(
            $exception->getMessage(),
            status: Response::HTTP_UNAUTHORIZED,
        );
    }
}
