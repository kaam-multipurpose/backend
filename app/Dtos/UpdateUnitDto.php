<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Dtos\Abstract\AbstractDto;

final readonly class UpdateUnitDto extends AbstractDto
{
    public function __construct(
        ?string $name = null,
        ?string $symbol = null,
    ) {}
}
