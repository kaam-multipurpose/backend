<?php

namespace App\Utils\Response;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

final readonly class ApiResponse
{
    public function __construct(
        public bool $success,
        public string $message,
        public mixed $payload,
        public int $statusCode,
    ) {}

    public static function success($data = null, string $message = 'Success', int $status = 200): JsonResponse
    {
        return self::build(true, $message, payload: $data, statusCode: $status);
    }

    public static function error(string $message = 'Error', $errors = null, int $status = 422): JsonResponse
    {
        return self::build(false, $message, payload: $errors, statusCode: $status);
    }

    private static function build(bool $success, string $message, mixed $payload = null, int $statusCode = 200): JsonResponse
    {
        return new self(
            success: $success,
            message: $message,
            payload: $payload,
            statusCode: self::validateStatusCode($statusCode),
        )->response();
    }

    private static function validateStatusCode(int $code): int
    {
        return ($code >= 100 && $code < 600) ? $code : 500;
    }

    private function response(): JsonResponse
    {
        return Response::json([
            'success' => $this->success,
            'message' => $this->message,
            $this->success ? 'data' : 'errors' => $this->payload,
        ], $this->statusCode);
    }
}
