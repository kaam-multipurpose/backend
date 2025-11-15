<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Dtos\Abstract\AbstractDto;

final readonly class ChangePasswordDto extends AbstractDto
{
    public function __construct(
        public string $currentPassword,
        public string $newPassword
    ) {}

    public static function fromValidated(array $data): static
    {
        return new self(
            currentPassword: $data['current_password'],
            newPassword: $data['new_password']
        );
    }

    public function toArray(): array
    {
        return [
            'password' => $this->newPassword,
        ];
    }
}
