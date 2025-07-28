<?php

namespace App\Exceptions\Handlers;

use App\Exceptions\CategoryServiceException;
use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasLogger;
use Illuminate\Http\JsonResponse;

class CategoryServiceExceptionHandler
{
    use HasLogger;

    public static function handle(CategoryServiceException $exception): JsonResponse
    {
        self::logException($exception, "Category Service Failed");

        return ApiResponse::error(
            $exception->getMessage(),
            status: $exception->getCode()
        );
    }
}
