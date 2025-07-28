<?php

namespace App\Utils\Trait;

use App\Utils\Logger\Contract\LoggerContract;
use App\Utils\Logger\Dto\LoggerContextDto;

trait HasLogger
{
    protected static function logException(\Throwable $exception, string $message, $extra = []): void
    {
        app(LoggerContract::class)->error($message, LoggerContextDto::fromException($exception, $extra));
    }

    protected function log(string $level, string $message, array $extra = []): void
    {
        $user = $this->getLoggedInUser();
        $context = LoggerContextDto::fromUser($user, $extra);
        $this->logger->{$level}($message, $context);
    }

}
