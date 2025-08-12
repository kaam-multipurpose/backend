<?php

namespace App\Utils\Trait;

use App\Utils\Logger\Contract\LoggerContract;
use App\Utils\Logger\Dto\LoggerContextDto;

trait HasLogger
{
    protected static function logException(\Throwable $exception, string $message, $extra = []): void
    {
        app(LoggerContract::class)->error($message, LoggerContextDto::fromException($exception, self::getLoggedInUser(), $extra));
    }

    protected function logInfo(string $message, array $extra = []): void
    {
        app(LoggerContract::class)->info($message, self::extractUser($extra));
    }

    private static function extractUser(array $extra = []): LoggerContextDto
    {
        $user = self::getLoggedInUser();
        return LoggerContextDto::fromUser($user, $extra);
    }

    protected function logError(string $message, array $extra = []): void
    {
        app(LoggerContract::class)->error($message, self::extractUser($extra));
    }

    protected function logWarning(string $message, array $extra = []): void
    {
        app(LoggerContract::class)->warning($message, self::extractUser($extra));
    }

}
