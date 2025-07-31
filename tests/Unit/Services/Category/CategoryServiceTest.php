<?php

namespace Tests\Unit\Services\Category;

use App\Dto\CreateCategoryDto;
use App\Dto\GetPaginatedCategoriesDto;
use App\Exceptions\CategoryServiceException;
use App\Models\Category;
use App\Services\Contracts\CategoryServiceContract;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    protected CategoryServiceContract $categoryService;

    /**
     * @throws CategoryServiceException
     */
    public function test_it_can_add_a_category(): void
    {
        $dto = new CreateCategoryDto('my category');

        $this->categoryService->createCategory($dto);

        $this->assertDatabaseHas('categories', $dto->toArray());
    }

    /**
     * @throws CategoryServiceException
     */
    public function test_it_can_fetch_paginated_categories(): void
    {
        Category::factory(20)->create();

        $dto = new GetPaginatedCategoriesDto(); // Defaults assumed

        $result = $this->categoryService->getPaginatedCategories($dto);

        $this->assertSame(5, $result->perPage());
        $this->assertSame(1, $result->currentPage());
        $this->assertCount(5, $result->items()); // Optional but nice
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Resolve the service from the container once
        $this->categoryService = $this->app->make(CategoryServiceContract::class);
    }
}
