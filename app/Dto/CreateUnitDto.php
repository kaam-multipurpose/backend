<?php

namespace App\Dto;

final readonly class createUnitDto implements Contract\DtoContract
{

    public function __construct(public string $name)
    {

    }

    public static function fromValidated(array $data): self
    {
        return new self(
            name: $data['name'],
        );
    }

    public function toArray(): array
    {
        return [
            "name" => $this->name
        ];
    }
}
