<?php

declare(strict_types=1);

namespace App\Exceptions\Handlers;

use App\Exceptions\AbstractServiceException;
use App\Exceptions\Handlers\Trait\HasHandlerRender;
use Illuminate\Http\JsonResponse;

final class ServiceExceptionsHandler
{
    use HasHandlerRender;

    public static function handle(AbstractServiceException $exception): JsonResponse
    {
        return self::render($exception, $exception->getMessage(), status: $exception->getCode());
    }
}
