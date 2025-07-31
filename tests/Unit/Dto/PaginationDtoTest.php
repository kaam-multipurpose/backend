<?php

namespace Tests\Unit\Dto;

use App\Dto\GetPaginatedCategoriesDto;
use Tests\TestCase;

class PaginationDtoTest extends TestCase
{
    public function test_default_values_are_set_when_none_provided(): void
    {
        $dto = new GetPaginatedCategoriesDto([]);
        $this->assertSame(1, $dto->page);
        $this->assertSame(5, $dto->row);
    }

    public function test_custom_values_are_respected(): void
    {
        $dto = new GetPaginatedCategoriesDto(['page' => 2, 'row' => 10]);
        $this->assertSame(2, $dto->page);
        $this->assertSame(10, $dto->row);
    }

    public function test_from_validated_sets_defaults(): void
    {
        $dto = GetPaginatedCategoriesDto::fromValidated([]);
        $this->assertSame(1, $dto->page);
        $this->assertSame(5, $dto->row);
    }

    public function test_from_validated_sets_custom_values(): void
    {
        $dto = GetPaginatedCategoriesDto::fromValidated(['page' => 4, 'row' => 25]);
        $this->assertSame(4, $dto->page);
        $this->assertSame(25, $dto->row);
    }

    public function test_search_is_included_in_output(): void
    {
        $dto = GetPaginatedCategoriesDto::fromValidated(['search' => 'notebooks']);
        $this->assertSame('notebooks', $dto->search);

        $expected = [
            'search' => 'notebooks',
            'page' => 1,
            'row' => 5,
        ];

        $this->assertSame($expected, $dto->toArray());
    }
}
