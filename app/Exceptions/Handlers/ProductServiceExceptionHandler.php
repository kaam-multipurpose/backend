<?php

namespace App\Exceptions\Handlers;

use App\Exceptions\CategoryServiceException;
use App\Exceptions\ProductServiceException;
use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasLogger;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ProductServiceExceptionHandler
{
    use HasLogger;

    public static function handle(ProductServiceException $exception): JsonResponse
    {
        self::logException($exception, "Product Service Failed");

        return ApiResponse::error(
            $exception->getMessage(),
            status: $exception->getCode(),
        );
    }
}
