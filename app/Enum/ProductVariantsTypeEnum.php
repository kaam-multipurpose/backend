<?php

namespace App\Enum;

use App\Enum\Contract\EnumContract;
use App\Enum\Trait\EnumTrait;

enum ProductVariantsTypeEnum: string implements EnumContract
{
    use EnumTrait;

    case SIZE = "size";
    case COLOR = "colour";
    case TYPE = "type";
}
