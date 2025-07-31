<?php

namespace App\Exceptions;

use Exception;

class UnitServiceException extends Exception
{
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        $code = $previous?->getCode() ?? 500;
        parent::__construct($message, code: $code, previous: $previous);
    }
}
