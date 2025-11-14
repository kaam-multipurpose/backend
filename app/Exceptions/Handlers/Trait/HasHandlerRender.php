<?php

declare(strict_types=1);

namespace App\Exceptions\Handlers\Trait;

use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Http\JsonResponse;
use Throwable;

trait HasHandlerRender
{
    use HasAuthenticatedUser;
    use HasLogger;

    public static function render(Throwable $exception, string $message, int $status): JsonResponse
    {
        self::logError($exception->getMessage(), [
            'trace' => $exception->getTraceAsString(),
        ]);
        self::logException($exception, $message);

        return ApiResponse::error($message, status: $status);
    }
}
