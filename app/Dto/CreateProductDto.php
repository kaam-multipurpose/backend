<?php

namespace App\Dto;

use App\Dto\Contract\DtoContract;
use App\Enum\ProductVariantsTypeEnum;

final readonly class CreateProductDto implements DtoContract
{
    /**
     * @param string $name
     * @param int $categoryId
     * @param int|null $costPrice
     * @param array<CreateVariantDto> $variants
     * @param ProductVariantsTypeEnum|null $variantType
     */
    public function __construct(
        public string                   $name,
        public int                      $categoryId,
        public ?int                     $costPrice = null,
        public ?array                   $variants = null,
        public ?ProductVariantsTypeEnum $variantType = null,
    )
    {

    }

    public static function fromValidated(array $data): self
    {
        return new self(
            name: $data['name'],
            categoryId: $data['category_id'],
            costPrice: $data['cost_price'] ?? null,
            variants: array_map(
                fn(array $variant) => CreateVariantDto::fromValidated($variant),
                $data['variants'] ?? []
            ),
            variantType: ProductVariantsTypeEnum::tryFrom($data['variant_type'] ?? ""),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'category_id' => $this->categoryId,
            'variant_type' => $this->variantType,
        ];
    }
}
