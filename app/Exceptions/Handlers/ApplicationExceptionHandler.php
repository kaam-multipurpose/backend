<?php

declare(strict_types=1);

namespace App\Exceptions\Handlers;

use App\Exceptions\ApplicationException;
use App\Exceptions\Handlers\Trait\HasHandlerRender;
use Illuminate\Http\JsonResponse;

final class ApplicationExceptionHandler
{
    use HasHandlerRender;

    public static function handle(ApplicationException $exception): JsonResponse
    {
        return self::render($exception, $exception->getMessage(), status: $exception->getCode());
    }
}
