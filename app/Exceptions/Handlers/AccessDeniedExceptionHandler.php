<?php

namespace App\Exceptions\Handlers;

use App\Exceptions\CategoryServiceException;
use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasLogger;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccessDeniedExceptionHandler
{
    use HasLogger;

    public static function handle(AccessDeniedHttpException $exception): JsonResponse
    {
        self::logException($exception, "Access Denied");

        return ApiResponse::error(
            $exception->getMessage(),
            status: Response::HTTP_FORBIDDEN,
        );
    }
}
