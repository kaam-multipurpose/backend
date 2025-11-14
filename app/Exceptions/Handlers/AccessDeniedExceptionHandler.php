<?php

declare(strict_types=1);

namespace App\Exceptions\Handlers;

use App\Exceptions\Handlers\Trait\HasHandlerRender;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

final class AccessDeniedExceptionHandler
{
    use HasHandlerRender;

    public static function handle(AccessDeniedHttpException $exception): JsonResponse
    {
        return self::render($exception, 'Access Denied', Response::HTTP_FORBIDDEN);
    }
}
