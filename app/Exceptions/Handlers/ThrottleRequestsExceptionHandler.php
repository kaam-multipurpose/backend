<?php

declare(strict_types=1);

namespace App\Exceptions\Handlers;

use App\Exceptions\Handlers\Trait\HasHandlerRender;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ThrottleRequestsExceptionHandler
{
    use HasHandlerRender;

    public static function handle(ThrottleRequestsException $exception): JsonResponse
    {
        return self::render($exception, 'Too Many Attempts', status: Response::HTTP_TOO_MANY_REQUESTS);
    }
}
