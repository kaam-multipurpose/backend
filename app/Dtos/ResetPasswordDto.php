<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Dtos\Abstract\AbstractDto;
use Illuminate\Support\Facades\Hash;

final readonly class ResetPasswordDto extends AbstractDto
{
    public function __construct(
        public string $email,
        public string $token,
        public string $password,
    ) {}

    public static function fromValidated(array $data): static
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
