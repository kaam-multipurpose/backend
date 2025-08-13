<?php

namespace App\Exceptions\Handlers\Trait;

use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Http\JsonResponse;

trait HasHandlerRender
{
    use HasLogger, HasAuthenticatedUser;

    public static function render(\Throwable $exception, string $message, int $status): JsonResponse
    {
        self::logException($exception, $message);
        return ApiResponse::error($exception->getMessage(), status: $status);
    }

}
