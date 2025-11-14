<?php

declare(strict_types=1);

namespace App\Dto;

use App\Dto\Abstract\AbstractPaginationDto;
use App\Dto\Contract\DtoContract;

final readonly class GetPaginatedVariantTypesDto extends AbstractPaginationDto implements DtoContract
{
    public function __construct(
        ?array $defaultPaginationProps = [],
    ) {
        parent::__construct($defaultPaginationProps);
    }

    public static function fromValidated(array $data): self
    {
        $default = array_filter(
            $data,
            fn ($item): bool => in_array($item, self::defaultKeys()),
            ARRAY_FILTER_USE_KEY
        );

        return new self(
            defaultPaginationProps: $default,
        );
    }

    public function toArray(): array
    {
        return $this->defaultToArray();
    }
}
