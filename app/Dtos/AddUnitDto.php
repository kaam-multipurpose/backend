<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Dtos\Abstract\AbstractDto;

final readonly class AddUnitDto extends AbstractDto
{
    public function __construct(
        public string $name,
        public string $symbol,
        public ?int $quantity = null,
    ) {
    }

    public static function fromValidated(array $data): static
    {
        return new self(
            name: $data['name'],
            symbol: $data['symbol'],
            quantity: $data['quantity'] ?? null,
        );
    }
}
