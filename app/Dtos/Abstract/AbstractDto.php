<?php

declare(strict_types=1);

namespace App\Dtos\Abstract;

use App\Dtos\Contract\DtoContract;
use ReflectionClass;
use ReflectionProperty;

abstract readonly class AbstractDto implements DtoContract
{
    public static function fromValidated(array $data): static
    {
        return new static(...$data);
    }

    final public function toFilledArray(): array
    {
        return array_filter($this->toArray());
    }

    public function toArray(): array
    {
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        $result = [];
        foreach ($properties as $property) {
            $name = $property->getName();
            $value = $property->getValue($this);

            if ($value instanceof DtoContract) {
                $value = $value->toArray();
            }

            if (is_array($value)) {
                $value = array_map(
                    fn($item) => $item instanceof DtoContract ? $item->toArray() : $item,
                    $value
                );
            }

            $result[$name] = $value;
        }

        return $result;
    }
}
