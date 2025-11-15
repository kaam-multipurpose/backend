<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Dtos\Abstract\AbstractDto;

final readonly class AddVariantTypeValueDto extends AbstractDto
{
    public function __construct(
        public string $name
    ) {}

    public static function fromValidated(array $data): static
    {
        return new self(
            name: $data['name'],
        );
    }
}
