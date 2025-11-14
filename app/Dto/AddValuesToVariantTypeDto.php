<?php

declare(strict_types=1);

namespace App\Dto;

use App\Models\VariantType;

final class AddValuesToVariantTypeDto
{
    /**
     * @param  AddVariantTypeValueDto[]  $values
     */
    public function __construct(
        private(set) array $values {
            set(array $values) {
                $this->values = array_map(
                    fn (AddVariantTypeValueDto|array|string $value): AddVariantTypeValueDto => $value instanceof AddVariantTypeValueDto
                        ? $value
                        : new AddVariantTypeValueDto(
                            name: is_array($value) ?
                                ($value['name'] ?? '') :
                                (string) $value
                        ),
                    $values
                );
            }
        },
        public readonly VariantType $variantType,
    ) {}

    public static function fromValidated(array $data, VariantType $variantType): self
    {
        return new self(
            values: $data['values'],
            variantType: $variantType,
        );
    }

    public function toArray(): array
    {
        return [
            'values' => array_map(fn (AddVariantTypeValueDto $value): array => $value->toArray(), $this->values),
            'variant_type' => $this->variantType,
        ];
    }
}
