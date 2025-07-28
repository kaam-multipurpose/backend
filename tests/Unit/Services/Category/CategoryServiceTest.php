<?php

namespace Tests\Unit\Services\Category;

use App\Dto\CreateCategoryDto;
use App\Dto\GetPaginatedCategoriesDto;
use App\Models\Category;
use App\Services\Contracts\CategoryServiceContract;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @throws \Exception
     */
    public function test_system_can_add_category(): void
    {
        $categoryDto = new CreateCategoryDto('my category');
        $mockerCategory = $this->app->make(CategoryServiceContract::class);
        $mockerCategory->createCategory($categoryDto);

        $this->assertDatabaseHas('categories', $categoryDto->toArray());
    }

    /**
     * @throws \Exception
     */
    public function test_system_can_get_paginated_category(): void
    {
        Category::factory(20)->create();
        $paginatedCategoryDto = new GetPaginatedCategoriesDto;

        $mockerCategory = $this->app->make(CategoryServiceContract::class);
        $result = $mockerCategory->getPaginatedCategories($paginatedCategoryDto);

        // Assert pagination properties
        $this->assertEquals(5, $result->perPage());
        $this->assertEquals(1, $result->currentPage());

        // Optional: assert that 5 items are returned
        $this->assertCount(5, $result->items());

    }
}
