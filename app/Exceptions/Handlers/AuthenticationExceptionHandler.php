<?php

declare(strict_types=1);

namespace App\Exceptions\Handlers;

use App\Exceptions\Handlers\Trait\HasHandlerRender;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class AuthenticationExceptionHandler
{
    use HasHandlerRender;

    public static function handle(AuthenticationException $exception): JsonResponse
    {
        return self::render($exception, 'Authentication Failed', Response::HTTP_UNAUTHORIZED);
    }
}
