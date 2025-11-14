<?php

declare(strict_types=1);

namespace App\Dto;

use App\Dto\Contract\DtoContract;

final class AddVariantTypeValueDto implements DtoContract
{
    public function __construct(
        public string $name
    ) {}

    public static function fromValidated(array $data): self
    {
        return new self(
            name: $data['name'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
