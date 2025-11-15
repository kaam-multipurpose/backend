<?php

declare(strict_types=1);

namespace App\Dtos\Contract;

interface DtoContract
{
    public static function fromValidated(array $data): static;

    public function toArray(): array;
}
