<?php

use App\Exceptions\CategoryServiceException;
use App\Exceptions\Handlers\AccessDeniedExceptionHandler;
use App\Exceptions\Handlers\AuthenticationExceptionHandler;
use App\Exceptions\Handlers\CategoryServiceExceptionHandler;
use App\Exceptions\Handlers\NotFoundHttpExceptionHandler;
use App\Exceptions\Handlers\ProductServiceExceptionHandler;
use App\Exceptions\Handlers\UnitServiceExceptionHandler;
use App\Exceptions\Handlers\ValidationExceptionHandler;
use App\Exceptions\ProductServiceException;
use App\Exceptions\UnitServiceException;
use App\Utils\Logger\Contract\LoggerContract;
use App\Utils\Logger\Dto\LoggerContextDto;
use App\Utils\Response\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (AccessDeniedHttpException $exception): JsonResponse {
            return AccessDeniedExceptionHandler::handle($exception);
        });
        $exceptions->renderable(function (AuthenticationException $exception): JsonResponse {
            return AuthenticationExceptionHandler::handle($exception);
        });
        $exceptions->renderable(function (ValidationException $exception): JsonResponse {
            return ValidationExceptionHandler::handle($exception);
        });
        $exceptions->renderable(function (NotFoundHttpException $exception): JsonResponse {
            return NotFoundHttpExceptionHandler::handle($exception);
        });
        $exceptions->renderable(function (\Throwable $exception): JsonResponse {
            app(LoggerContract::class)->error('Unexpected Error', LoggerContextDto::fromException($exception));

            return ApiResponse::error(
                'Something went wrong',
                status: Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        });
    })->create();
