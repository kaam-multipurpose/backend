<?php

namespace App\Exceptions\Handlers;

use App\Exceptions\Handlers\Trait\HasHandlerRender;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccessDeniedExceptionHandler
{
    use HasLogger, HasAuthenticatedUser, HasHandlerRender;

    public static function handle(AccessDeniedHttpException $exception): JsonResponse
    {
        return self::render($exception, 'Access Denied', Response::HTTP_FORBIDDEN);
    }
}
