<?php

namespace App\Enum;

use App\Enum\Contract\EnumContract;
use App\Enum\Trait\EnumTrait;

enum PaginationEnum: string implements EnumContract
{
    use EnumTrait;

    case ROW = 'row';
    case PAGE = 'page';

    public static function rules(): array
    {
        return [
            self::ROW->value => ['integer'],
            self::PAGE->value => ['integer'],
        ];
    }
}
