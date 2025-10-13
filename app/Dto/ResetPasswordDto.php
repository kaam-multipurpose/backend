<?php

namespace App\Dto;

use App\Dto\Contract\DtoContract;
use Illuminate\Support\Facades\Hash;

final readonly class ResetPasswordDto implements DtoContract
{
    public function __construct(
        public string $email,
        public string $token,
        public string $password,
    ) {}

    public static function fromValidated(array $data): self
    {
        return new self(
            email: $data['email'],
            token: $data['token'],
            password: $data['password'],
        );
    }

    public function toArray(): array
    {
        return [
            'password' => Hash::make($this->password),
        ];
    }
}
