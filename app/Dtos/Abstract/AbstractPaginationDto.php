<?php

declare(strict_types=1);

namespace App\Dtos\Abstract;

use App\Enums\PaginationEnum;

abstract readonly class AbstractPaginationDto extends AbstractDto
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

    final public static function fromValidated(array $data): static
    {
        return new static(self::extractDefault($data));
    }

    final public function toArray(): array
    {
        return [
            'page' => $this->page,
            'row' => $this->row,
        ];
    }

    protected static function extractDefault($data): array
    {
        return array_filter(
            $data,
            fn ($item): bool => in_array($item, self::defaultKeys()),
            ARRAY_FILTER_USE_KEY
        );
    }
}
