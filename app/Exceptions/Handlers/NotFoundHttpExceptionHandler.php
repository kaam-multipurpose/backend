<?php

namespace App\Exceptions\Handlers;

use App\Exceptions\Handlers\Trait\HasHandlerRender;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotFoundHttpExceptionHandler
{
    use HasHandlerRender;

    public static function handle(NotFoundHttpException $exception): JsonResponse
    {
        $original = $exception->getPrevious();

        if ($original instanceof ModelNotFoundException) {
            $model = class_basename($original->getModel());
            return self::render($exception, "{$model} model not found", Response::HTTP_NOT_FOUND);
        }

        return self::render($exception, "Unknown resource", Response::HTTP_NOT_FOUND);
    }
}
