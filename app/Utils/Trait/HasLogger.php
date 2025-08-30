<?php

namespace App\Utils\Trait;

use App\Utils\Logger\Dto\LoggerContextDto;
use App\Utils\Logger\Logger;

trait HasLogger
{
    protected static function logException(\Throwable $exception, string $message, $extra = []): void
    {
        Logger::error($message, LoggerContextDto::fromException($exception, self::getLoggedInUser(), $extra));
    }

    protected static function logInfo(string $message, array $extra = []): void
    {
        Logger::info($message, self::extractUser($extra));
    }

    protected static function logError(string $message, array $extra = []): void
    {
        Logger::error($message, self::extractUser($extra));
    }

    protected static function logWarning(string $message, array $extra = []): void
    {
        Logger::warning($message, self::extractUser($extra));
    }

    private static function extractUser(array $extra = []): LoggerContextDto
    {
        $user = self::getLoggedInUser();

        return LoggerContextDto::fromUser($user, $extra);
    }
}
