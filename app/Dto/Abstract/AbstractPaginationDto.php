<?php

namespace App\Dto\Abstract;

use App\Enum\PaginationEnum;

abstract readonly class AbstractPaginationDto
{
    public int $page;

    public int $row;

    public function __construct(?array $array)
    {
        $this->page = $array['page'] ?? 1;
        $this->row = $array['row'] ?? 5;
    }

    public static function defaultKeys(): array
    {
        return PaginationEnum::values();
    }

    public function defaultToArray(): array
    {
        return [
            'page' => $this->page,
            'row' => $this->row,
        ];
    }
}
