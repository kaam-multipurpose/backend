<?php

declare(strict_types=1);

namespace App\Enum\Trait;

trait EnumTrait
{
    public static function values(): array
    {
        return collect(self::cases())->pluck('value')->toArray();
    }
}
