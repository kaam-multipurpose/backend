<?php

declare(strict_types=1);

namespace App\Dtos;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Auth\Authenticatable;

final readonly class LoginServiceResponseDto
{
    public function __construct(
        public ?Authenticatable $user = null,
        public ?string $token = null,
        public ?CarbonInterface $expiresAt = null,
        public ?string $refreshToken = null,
        public ?CarbonInterface $refreshTokenExpiresAt = null,
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

    public function withToken(string $token, CarbonInterface $expiresAt): self
    {
        $currentData = $this->toArray();
        $currentData['token'] = $token;
        $currentData['expiresAt'] = $expiresAt;

        return new self(
            ...$currentData,
        );
    }

    public function withRefreshToken(string $refreshToken, CarbonInterface $expiresAt): self
    {
        $currentData = $this->toArray();
        $currentData['refreshToken'] = $refreshToken;
        $currentData['refreshTokenExpiresAt'] = $expiresAt;

        return new self(
            ...$currentData,
        );
    }
}
