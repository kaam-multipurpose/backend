<?php

namespace App\Dto;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;

final readonly class LoginServiceResponseDto
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public ?Authenticatable $user = null,
        public ?string $token = null,
        public ?Carbon $expiresAt = null,
        public ?string $refreshToken = null,
        public ?Carbon $refreshTokenExpiresAt = null,
    ) {
        //
    }

    public function withUser(Authenticatable $user): self
    {
        $currentData = $this->toArray();
        $currentData['user'] = $user;

        return new self(
            ...$currentData,
        );
    }

    /**
     * @return array{
     *     user: Authenticatable|null,
     *     token: string|null,
     *     expiresAt: Carbon|null,
     *     refreshToken: string|null,
     *     refreshTokenExpiresAt: Carbon|null
     * }
     */
    public function toArray(): array
    {
        return [
            'user' => $this->user,
            'token' => $this->token,
            'expiresAt' => $this->expiresAt,
            'refreshToken' => $this->refreshToken,
            'refreshTokenExpiresAt' => $this->refreshTokenExpiresAt,
        ];
    }

    public function withToken(string $token, Carbon $expiresAt): self
    {
        $currentData = $this->toArray();
        $currentData['token'] = $token;
        $currentData['expiresAt'] = $expiresAt;

        return new self(
            ...$currentData,
        );
    }

    public function withRefreshToken(string $refreshToken, Carbon $expiresAt): self
    {
        $currentData = $this->toArray();
        $currentData['refreshToken'] = $refreshToken;
        $currentData['refreshTokenExpiresAt'] = $expiresAt;

        return new self(
            ...$currentData,
        );
    }
}
