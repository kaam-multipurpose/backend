<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Dtos\Abstract\AbstractDto;

final readonly class AddCategoryDto extends AbstractDto
{
    public function __construct(
        public string $name,
        public ?array $variantTypeIds = null,
        public bool $isSubcategory = false,
        public bool $hasAdditionalVariantType = false
    ) {}

    public static function fromValidated(array $data): static
    {
        return new self(
            name: $data['name'],
            variantTypeIds: $data['variant_type_ids'] ?? [],
            hasAdditionalVariantType: $data['has_additional_variant_type'] ?? false,
        );
    }

    public function isSubCategory(): static
    {
        $data = $this->toFullArray();
        $data['isSubcategory'] = true;

        return new static(
            ...$data
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }

    private function toFullArray(): array
    {
        return [
            'name' => $this->name,
            'variantTypeIds' => $this->variantTypeIds,
            'hasAdditionalVariantType' => $this->hasAdditionalVariantType,
            'isSubcategory' => $this->isSubcategory,
        ];
    }
}
