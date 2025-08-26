<?php

namespace App\Utils\Logger\Dto;

use Illuminate\Contracts\Auth\Authenticatable;

final readonly class LoggerContextDto
{
    public function __construct(
        public ?int $userId = null,
        public ?string $email = null,
        public ?string $role = null,
        public ?string $exceptionClass = null,
        public ?string $trace = null,
        public array $extra = [],
        public ?string $endpoint = null,
    ) {}

    public static function fromUser(?Authenticatable $user = null, array $extra = []): self
    {
        return self::build(self::extractUserInfo($user), $extra);
    }

    public static function fromException(\Throwable $e, ?Authenticatable $user = null, array $extra = []): self
    {
        return self::build(self::extractUserInfo($user), $extra, $e);
    }

    private static function build(array $userInfo, array $extra = [], ?\Throwable $e = null): self
    {
        return new self(
            userId: $userInfo['userId'],
            email: $userInfo['email'],
            role: $userInfo['role'],
            exceptionClass: $e ? $e::class : null,
            trace: $e?->getTraceAsString(),
            extra: $e ? array_merge(['message' => $e->getMessage()], $extra) : $extra,
        );
    }

    private static function extractUserInfo(?Authenticatable $user): array
    {
        return [
            'userId' => $user?->id,
            'email' => $user?->email,
            'role' => $user
                    ? $user->getRoleNames()->first()
                : 'guest',
        ];
    }

    public function toArray(): array
    {
        return array_merge([
            'user_id' => $this->userId,
            'email' => $this->email,
            'role' => $this->role,
            'exception' => $this->exceptionClass,
            'endpoint' => $this->endpoint,
        ], $this->extra,[$this->trace]);
    }
}
