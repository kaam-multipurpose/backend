<?php

declare(strict_types=1);

namespace App\Enum\Contract;

interface EnumContract
{
    public static function values(): array;
}
