<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Dtos\Abstract\AbstractDto;

final readonly class AddVariantTypeDto extends AbstractDto
{
    public array $values;

    public function __construct(
        public string $name,
        array $values,
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
        return new self(
            name: $data['name'],
            values: $data['values'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }

    public function getValues(): array
    {
        return $this->values;
    }
}
