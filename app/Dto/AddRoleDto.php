<?php

namespace App\Dto;

use App\Dto\Contract\DtoContract;

final readonly class AddRoleDto implements DtoContract
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $role,
        public array $permissions,
    ) {}

    public static function fromValidated(array $data): self
    {
        return new self(
            ...$data
        );
    }

    public function toArray(): array
    {
        return [
            'role' => $this->role,
            'permissions' => $this->permissions,
        ];
    }
}
