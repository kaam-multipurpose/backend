<?php

namespace App\Enum;

use App\Enum\Contract\EnumContract;
use App\Enum\Trait\EnumTrait;

enum ProductUnitsEnum: string implements EnumContract
{

    use EnumTrait;

    case PIECE = 'piece';
    case DOZEN = 'dozen';
    case REAM = 'ream';
    case PACKET = 'packet';
    case CARTON = 'carton';

}
