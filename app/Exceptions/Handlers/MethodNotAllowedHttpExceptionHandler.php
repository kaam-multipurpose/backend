<?php

declare(strict_types=1);

namespace App\Exceptions\Handlers;

use App\Exceptions\Handlers\Trait\HasHandlerRender;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

final class MethodNotAllowedHttpExceptionHandler
{
    use HasHandlerRender;

    public static function handle(MethodNotAllowedHttpException $exception): JsonResponse
    {
        return self::render($exception, $exception->getMessage(), status: Response::HTTP_TOO_MANY_REQUESTS);
    }
}
