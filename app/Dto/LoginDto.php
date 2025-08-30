<?php

namespace App\Dto;

use App\Dto\Contract\DtoContract;

final readonly class LoginDto implements DtoContract
{
    public function __construct(public string $email, public string $password) {}

    public static function fromValidated(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password'],
        );
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
