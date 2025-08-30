<?php

namespace App\Dto;

use App\Dto\Abstract\AbstractPaginationDto;
use App\Dto\Contract\DtoContract;

final readonly class GetPaginatedCategoriesDto extends AbstractPaginationDto implements DtoContract
{
    public function __construct(
        protected ?array $defaultPaginationProps = [],
        public ?string $search = null,
    ) {
        parent::__construct($defaultPaginationProps);
    }

    public static function fromValidated(array $data): self
    {
        $default = array_filter(
            $data,
            fn ($item) => in_array($item, self::defaultKeys()),
            ARRAY_FILTER_USE_KEY
        );

        return new self(
            defaultPaginationProps: $default,
            search: $data['search'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_merge([
            'search' => $this->search,
        ], $this->defaultToArray());
    }
}
