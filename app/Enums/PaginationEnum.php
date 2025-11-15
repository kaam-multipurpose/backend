<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Contract\EnumContract;
use App\Enums\Trait\EnumTrait;

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
