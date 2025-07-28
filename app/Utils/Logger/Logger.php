<?php

namespace App\Utils\Logger;

use App\Utils\Logger\Contract\LoggerContract;
use App\Utils\Logger\Dto\LoggerContextDto;
use Illuminate\Support\Facades\Log;


class Logger implements LoggerContract
{
    public function info(string $message, ?LoggerContextDto $extraDto = null): void
    {
        Log::info($message, $this->buildContext($extraDto));
    }

    protected function buildContext(?LoggerContextDto $contextDto): array
    {
        return $contextDto ? $contextDto->toArray() : ['user' => 'system'];
    }

    public function error(string $message, ?LoggerContextDto $extraDto = null): void
    {
        Log::error($message, $this->buildContext($extraDto));
    }

    public function warning(string $message, ?LoggerContextDto $extraDto = null): void
    {
        Log::warning($message, $this->buildContext($extraDto));
    }
}
