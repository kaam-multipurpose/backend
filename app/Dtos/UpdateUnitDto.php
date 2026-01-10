<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Dtos\Abstract\AbstractDto;

final readonly class UpdateUnitDto extends AbstractDto
{
    public function __construct(
        public ?string $name = null,
        public ?string $symbol = null,
        public ?string $quantity = null,
    ) {
    }

    public static function fromValidated(array $data): static
    {
        return new self(
            name: $data['name'] ?? null,
            symbol: $data['symbol'] ?? null,
            quantity: $data['quantity'] ?? null,
        );
    }
}
