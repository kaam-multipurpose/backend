<?php

namespace App\Dto;

use App\Dto\Contract\DtoContract;

final readonly class AddCategoryDto implements DtoContract
{
    public function __construct(
        public string $name,
        /**
         * @var int[]|null $variantTypeIds
         */
        public ?array $variantTypeIds = null,
        public bool $isSubcategory = false,
        public bool $hasAdditionalVariantType = false
    ) {}

    public static function fromValidated(array $data): self
    {
        return new self(
            name: $data['name'],
            variantTypeIds: $data['variant_type_ids'] ?? [],
            hasAdditionalVariantType: $data['has_additional_variant_type'] ?? false,
        );
    }

    public function isSubCategory(): self
    {
        $data = $this->toFullArray();
        $data['isSubcategory'] = true;

        return new self(
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
