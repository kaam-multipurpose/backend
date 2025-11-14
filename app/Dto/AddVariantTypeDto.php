<?php

declare(strict_types=1);

namespace App\Dto;

use App\Dto\Contract\DtoContract;

final class AddVariantTypeDto implements DtoContract
{
    /**
     * @param  AddVariantTypeValueDto[]  $values
     */
    public function __construct(
        public readonly string $name,
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
    ) {}

    public static function fromValidated(array $data): self
    {
        return new self(
            name: $data['name'],
            values: $data['values']
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
