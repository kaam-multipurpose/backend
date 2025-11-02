<?php

namespace App\Dto;

use App\Dto\Contract\DtoContract;

final readonly class ChangePasswordDto implements DtoContract
{
    public function __construct(
        public string $currentPassword,
        public string $newPassword
    ) {}

    public static function fromValidated(array $data): self
    {
        return new self(
            $data['current_password'],
            $data['new_password']
        );
    }

    public function toArray(): array
    {
        return [
            'password' => $this->newPassword,
        ];
    }
}
