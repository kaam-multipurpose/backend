<?php

declare(strict_types=1);

namespace App\Dtos\Abstract;

use App\Dtos\Contract\DtoContract;
use App\Enums\PaginationEnum;

abstract readonly class AbstractPaginationDto extends AbstractDto implements DtoContract
{
    public int $page;

    public int $row;

    public function __construct(?array $array = null)
    {
        $this->page = (int) ($array['page'] ?? 1);
        $this->row = (int) ($array['row'] ?? 5);
    }

    final public static function defaultKeys(): array
    {
        return PaginationEnum::values();
    }

    public static function fromValidated(array $data): static
    {
        $default = array_filter(
            $data,
            fn ($item): bool => in_array($item, self::defaultKeys()),
            ARRAY_FILTER_USE_KEY
        );

        return new static($default);
    }

    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'row' => $this->row,
        ];
    }
}
