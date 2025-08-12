<?php

namespace App\Utils\Logger\Dto;

use Illuminate\Contracts\Auth\Authenticatable;

final readonly class LoggerContextDto
{
    public function __construct(
        public ?int    $userId = null,
        public ?string $email = null,
        public ?string $role = null,
        public ?string $exceptionClass = null,
        public ?string $trace = null,
        public ?string $endpoint = null,
        public array   $extra = []
    )
    {
    }

    public static function fromUser(?Authenticatable $user = null, array $extra = []): self
    {
        return self::createInstance($user, $extra);
    }

    protected static function createInstance(?Authenticatable $user, $extra = []): self
    {
        return new self(
            userId: $user?->id,
            email: $user?->email,
            role: $user
                ? (method_exists($user, 'getRoleNames')
                    ? $user->getRoleNames()->first()
                    : 'user')
                : 'guest',
            extra: $extra
        );
    }

    public static function fromException(\Throwable $e, ?Authenticatable $user = null, array $extra = []): self
    {
        return new self(
            userId: $user?->id,
            email: $user?->email,
            role: $user
                ? (method_exists($user, 'getRoleNames')
                    ? $user->getRoleNames()->first()
                    : 'user')
                : 'guest',
            exceptionClass: get_class($e),
            trace: $e->getTraceAsString(),
            extra: array_merge(['message' => $e->getMessage()], $extra)
        );
    }

    public function toArray(): array
    {
        return array_merge([
            'user_id' => $this->userId,
            'email' => $this->email,
            'role' => $this->role,
            'exception' => $this->exceptionClass,
            'trace' => $this->trace,
            'endpoint' => $this->endpoint,
        ], $this->extra);
    }
}
