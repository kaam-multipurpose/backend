<?php

declare(strict_types=1);

namespace App\Dto\Abstract;

use App\Enum\PaginationEnum;

abstract readonly class AbstractPaginationDto
{
    public int $page;

    public int $row;

    public function __construct(?array $array)
    {
        $this->page = (int) ($array['page'] ?? 1);
        $this->row = (int) ($array['row'] ?? 5);
    }

    final public static function defaultKeys(): array
    {
        return PaginationEnum::values();
    }

    final public function defaultToArray(): array
    {
        return [
            'page' => $this->page,
            'row' => $this->row,
        ];
    }
}
