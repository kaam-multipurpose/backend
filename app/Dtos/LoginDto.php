<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Dtos\Abstract\AbstractDto;

final readonly class LoginDto extends AbstractDto
{
    public function __construct(
        public string $email,
        public string $password
    ) {}

    public static function fromValidated(array $data): static
    {
        return new self(
            email: $data['email'],
            password: $data['password'],
        );
    }
}
