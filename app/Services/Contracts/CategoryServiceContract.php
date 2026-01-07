<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Dtos\AddCategoryDto;
use App\Dtos\GetPaginatedCategoriesDto;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CategoryServiceContract
{
    public function addCategory(AddCategoryDto $dto, ?Category $parentCategory = null): Category;

    public function getCategories(GetPaginatedCategoriesDto $dto): LengthAwarePaginator;

    public function getCategory(Category $category): Category;
}
