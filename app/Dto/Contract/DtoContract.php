<?php

declare(strict_types=1);

namespace App\Dto\Contract;

interface DtoContract
{
    public static function fromValidated(array $data): self;

    public function toArray(): array;
}
