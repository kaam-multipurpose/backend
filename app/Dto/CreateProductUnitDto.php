<?php

namespace App\Dto;

use App\Dto\Contract\DtoContract;

final readonly class CreateProductUnitDto implements DtoContract
{
    public function __construct(
        public int     $unitId,
        public int     $conversionRate,
        public float   $multiplier,
        public ?bool   $isBase = null,
        public ?string $isMax = null,
    )
    {

    }

    public static function fromValidated(array $data): self
    {
        $multiplier = 1 + ($data['percentage'] / 100);

        return new self(
            unitId: $data['unit_id'],
            conversionRate: $data['conversion_rate'],
            multiplier: $multiplier,
            isBase: $data['is_base'] ?? null,
            isMax: $data['is_max'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'unit_id' => $this->unitId,
            'conversion_rate' => $this->conversionRate,
            'multiplier' => $this->multiplier,
            'is_base' => $this->isBase,
            'is_max' => $this->isMax,
        ];
    }
}
