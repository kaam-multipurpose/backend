<?php

use App\Exceptions\Handlers\AccessDeniedExceptionHandler;
use App\Exceptions\Handlers\AuthenticationExceptionHandler;
use App\Exceptions\Handlers\NotFoundHttpExceptionHandler;
use App\Exceptions\Handlers\ThrottleRequestsExceptionHandler;
use App\Exceptions\Handlers\ValidationExceptionHandler;
use App\Utils\Logger\Dto\LoggerContextDto;
use App\Utils\Logger\Logger;
use App\Utils\Response\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(fn (AccessDeniedHttpException $exception): JsonResponse => AccessDeniedExceptionHandler::handle($exception));
        $exceptions->renderable(fn (ThrottleRequestsException $exception): JsonResponse => ThrottleRequestsExceptionHandler::handle($exception));
        $exceptions->renderable(fn (AuthenticationException $exception): JsonResponse => AuthenticationExceptionHandler::handle($exception));
        $exceptions->renderable(fn (ValidationException $exception): JsonResponse => ValidationExceptionHandler::handle($exception));
        $exceptions->renderable(fn (NotFoundHttpException $exception): JsonResponse => NotFoundHttpExceptionHandler::handle($exception));
        $exceptions->renderable(function (\Throwable $exception): JsonResponse {
            Logger::error('Unexpected Error', LoggerContextDto::fromException($exception));

            return ApiResponse::error(
                'Something went wrong',
                status: Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        });
    })->create();
