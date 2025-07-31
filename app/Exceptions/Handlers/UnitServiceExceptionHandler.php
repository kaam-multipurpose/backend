<?php

namespace App\Exceptions\Handlers;

use App\Exceptions\UnitServiceException;
use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasLogger;
use Illuminate\Http\JsonResponse;

class UnitServiceExceptionHandler
{
    use HasLogger;

    public static function handle(UnitServiceException $exception): JsonResponse
    {
        self::logException($exception, 'Unit Service Failed');

        return ApiResponse::error(
            $exception->getMessage(),
            status: $exception->getCode(),
        );
    }
}
