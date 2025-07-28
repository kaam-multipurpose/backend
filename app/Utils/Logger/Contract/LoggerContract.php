<?php

namespace App\Utils\Logger\Contract;

use App\Utils\Logger\Dto\LoggerContextDto;

interface LoggerContract
{
    public function info(string $message, LoggerContextDto $extraDto): void;

    public function error(string $message, LoggerContextDto $extraDto): void;

    public function warning(string $message, LoggerContextDto $extraDto): void;
}
