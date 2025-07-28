<?php

namespace App\Dto;

use App\Dto\Contract\DtoContract;

final readonly class CreateCategoryDto implements DtoContract
{
    public function __construct(
        public ?string $name = null,
    )
    {
    }

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
