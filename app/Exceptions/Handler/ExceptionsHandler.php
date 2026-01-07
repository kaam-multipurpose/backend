<?php

namespace App\Exceptions\Handler;

use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ExceptionsHandler
{
    use HasAuthenticatedUser;
    use HasLogger;

    public static function handle(\Throwable $exception, Request $request)
    {
        self::logException($exception, $exception->getMessage());
        if ($request->expectsJson()) {

            $code = $exception->getCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = "Something went wrong.";
            $errors = null;

            if ($exception instanceof ValidationException) {
                $errors = $exception->errors();
                $message = "Validation Failed";
                $code = Response::HTTP_UNPROCESSABLE_ENTITY;
            } elseif ($exception instanceof AuthenticationException) {
                $message = "Authentication Failed";
                $code = Response::HTTP_UNAUTHORIZED;
            } elseif ($exception instanceof AccessDeniedHttpException) {
                $message = "Access Denied";
                $code = Response::HTTP_FORBIDDEN;
            }

            return ApiResponse::error($message, $errors, $code);
        }
    }
}