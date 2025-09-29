<?php

namespace App\Exceptions\Handlers;

use App\Exceptions\ApplicationException;
use App\Exceptions\Handlers\Trait\HasHandlerRender;
use Illuminate\Http\JsonResponse;

class ApplicationExceptionHandler
{
    use HasHandlerRender;

    public static function handle(ApplicationException $exception): JsonResponse
    {
        return self::render($exception, $exception->getMessage(), status: $exception->getCode());
    }
}
