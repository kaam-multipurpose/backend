<?php

namespace Tests\Unit\Dto;

use App\Dto\GetPaginatedCategoriesDto;
use Tests\TestCase;

class PaginationDtoTest extends TestCase
{
    public function test_it_sets_default_page_and_row_when_not_provided(): void
    {
        $dto = new GetPaginatedCategoriesDto([]);

        $this->assertEquals(1, $dto->page);
        $this->assertEquals(5, $dto->row);
    }

    public function test_it_sets_provided_pagination_values_correctly(): void
    {
        $dto = new GetPaginatedCategoriesDto(['page' => 2, 'row' => 10]);

        $this->assertEquals(2, $dto->page);
        $this->assertEquals(10, $dto->row);
    }

    public function test_from_validated_sets_default_values_if_none_provided(): void
    {
        $dto = GetPaginatedCategoriesDto::fromValidated([]);

        $this->assertEquals(1, $dto->page);
        $this->assertEquals(5, $dto->row);
    }

    public function test_from_validated_sets_custom_values(): void
    {

        $dto = GetPaginatedCategoriesDto::fromValidated([
            'page' => 4,
            'row' => 25,
        ]);

        $this->assertEquals(4, $dto->page);
        $this->assertEquals(25, $dto->row);
    }

    public function test_from_validated_sets_search_when_present(): void
    {

        $dto = GetPaginatedCategoriesDto::fromValidated([
            'search' => 'notebooks',
        ]);

        $this->assertEquals('notebooks', $dto->search);
        $this->assertEquals([
            'search' => 'notebooks',
            'page' => 1,
            'row' => 5,
        ], $dto->toArray());
    }
}
