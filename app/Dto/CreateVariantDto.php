<?php

namespace App\Dto;

use App\Dto\Contract\DtoContract;
use App\Enum\ProductVariantsTypeEnum;

final readonly class CreateVariantDto implements DtoContract
{
    public function __construct(
        public string $name,
        public int    $costPrice,
    )
    {

    }

    public static function fromValidated(array $data): self
    {
        return new self(
            name: $data['name'],
            costPrice: $data['cost_price'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'cost_price' => $this->costPrice,
        ];
    }
}
