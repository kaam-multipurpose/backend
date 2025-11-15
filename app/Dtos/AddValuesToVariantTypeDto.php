<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Dtos\Abstract\AbstractDto;
use App\Models\VariantType;
use InvalidArgumentException;

final readonly class AddValuesToVariantTypeDto extends AbstractDto
{
    public array $values;

    public function __construct(
        array $values,
        public VariantType $variantType,
    ) {
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

    public static function fromValidated(array $data): static
    {
        if (! isset($data['variant_type']) || ! $data['variant_type'] instanceof VariantType) {
            throw new InvalidArgumentException('The data must contain a variant_type key with a VariantType instance');
        }

        return new self(
            values: $data['values'] ?? [],
            variantType: $data['variant_type'],
        );
    }

    public static function fromValidatedWithVariantType(array $data, VariantType $variantType): static
    {
        return new static(
            values: $data['values'] ?? [],
            variantType: $variantType,
        );
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function toArray(): array
    {
        return [
            'values' => array_map(fn (AddVariantTypeValueDto $value): array => $value->toArray(), $this->values),
            'variant_type' => $this->variantType,
        ];
    }
}
