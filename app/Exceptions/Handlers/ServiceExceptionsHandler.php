<?php

namespace App\Exceptions\Handlers;

use App\Exceptions\AbstractServiceException;
use App\Exceptions\Handlers\Trait\HasHandlerRender;
use Illuminate\Http\JsonResponse;

class ServiceExceptionsHandler
{
    use HasHandlerRender;

    public static function handle(AbstractServiceException $exception): JsonResponse
    {
        return self::render($exception, $exception->getMessage(), status: $exception->getCode());
    }
}
