<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class ApplicationException extends Exception
{
    use HasAuthenticatedUser;
    use HasLogger;

    public function __construct(
        string $message = '',
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function render(Request $request)
    {
        self::logException($this, $this->getMessage());

        if ($request->expectsJson()) {
            return ApiResponse::error($this->message, status: $this->getCode());
        }
    }
}
