<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Dtos\Abstract\AbstractDto;

final readonly class AddRoleDto extends AbstractDto
{
    public function __construct(
        public string $role,
        public array $permissions,
    ) {}
}
