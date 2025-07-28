<?php

namespace App\Exceptions\Handlers;

use App\Exceptions\CategoryServiceException;
use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasLogger;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotFoundHttpExceptionHandler
{
    use HasLogger;

    public static function handle(NotFoundHttpException $exception): JsonResponse
    {
        $original = $exception->getPrevious();

        if ($original instanceof ModelNotFoundException) {
            $model = class_basename($original->getModel());

            self::logException($exception, "{$model} model not found");

            return ApiResponse::error(
                "{$model} not found",
                status: Response::HTTP_NOT_FOUND
            );
        }

        self::logException($exception, "'Unknown resource");

        return ApiResponse::error(
            'Unknown Resource',
            status: Response::HTTP_NOT_FOUND
        );
    }
}
