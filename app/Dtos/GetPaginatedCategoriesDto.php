<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Dtos\Abstract\AbstractPaginationDto;

final readonly class GetPaginatedCategoriesDto extends AbstractPaginationDto
{
    public function __construct(
        ?array $defaultPaginationProps = null,
    ) {
        parent::__construct($defaultPaginationProps);
    }
}
