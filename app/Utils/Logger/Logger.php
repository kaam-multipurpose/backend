<?php

namespace App\Utils\Logger;

use App\Utils\Logger\Dto\LoggerContextDto;
use Illuminate\Support\Facades\Log;

class Logger
{
    public static function info(string $message, ?LoggerContextDto $extraDto = null): void
    {
        Log::info($message, self::buildContext($extraDto));
    }

    public static function error(string $message, ?LoggerContextDto $extraDto = null): void
    {
        Log::error($message, self::buildContext($extraDto));
    }

    public static function warning(string $message, ?LoggerContextDto $extraDto = null): void
    {
        Log::warning($message, self::buildContext($extraDto));
    }

    protected static function buildContext(?LoggerContextDto $contextDto): array
    {
        return $contextDto ? $contextDto->toArray() : ['user' => 'system'];
    }
}
