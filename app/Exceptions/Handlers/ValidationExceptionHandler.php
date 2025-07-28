<?php

namespace App\Exceptions\Handlers;

use App\Exceptions\CategoryServiceException;
use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ValidationExceptionHandler
{
    use HasLogger;

    public static function handle(ValidationException $exception): JsonResponse
    {
        self::logException($exception, 'Validation Failed', [
            'errors' => $exception->errors(),
        ]);

        return ApiResponse::error(
            'Validation Error',
            $exception->errors(),
            status: Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
