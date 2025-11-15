<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Dtos\AddCategoryDto;
use App\Dtos\GetPaginatedCategoriesDto;
use App\Exceptions\CategoryServiceException;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CategoryServiceContract
{
    /**
     * Category is independent of variant type
     */

    /**
     * @throws CategoryServiceException
     */
    public function addCategory(AddCategoryDto $dto, ?Category $parentCategory = null): Category;

    /**
     * @throws CategoryServiceException
     */
    public function getCategories(GetPaginatedCategoriesDto $dto): LengthAwarePaginator;

    /**
     * @throws CategoryServiceException
     */
    public function getCategory(Category $category): Category;
}
