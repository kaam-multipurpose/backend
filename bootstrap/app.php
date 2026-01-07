<?php

declare(strict_types=1);


use App\Exceptions\Handler\ExceptionsHandler;
use App\Utils\Logger\Dto\LoggerContextDto;
use App\Utils\Logger\Logger;
use App\Utils\Response\ApiResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $exceptions->renderable(fn(Throwable $exception, Request $request) => ExceptionsHandler::handle($exception,
            $request));
    })->create();
