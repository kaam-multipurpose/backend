<?php

namespace App\Enum\Trait;

trait EnumTrait
{
    public static function values(): array
    {
        return collect(self::cases())->pluck('value')->toArray();
    }
}
