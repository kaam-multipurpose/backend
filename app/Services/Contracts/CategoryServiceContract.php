<?php

namespace App\Services\Contracts;

use App\Dto\CreateCategoryDto;
use App\Dto\GetPaginatedCategoriesDto;
use App\Exceptions\CategoryServiceException;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface CategoryServiceContract extends GenericServiceContract
{
    /**
     * @throws CategoryServiceException
     */
    public function createCategory(CreateCategoryDto $createCategoryDto): Category;

    /**
     * @throws CategoryServiceException
     */
    public function updateCategory(Category $category, CreateCategoryDto $createCategoryDto): Category;

    /**
     * @throws CategoryServiceException
     */
    public function deleteCategory(Category $category): bool;

    /**
     * @throws CategoryServiceException
     */
    public function getAllCategories(): Collection;

    //    public function getPaginatedCategoryProducts(Category $category): LengthAwarePaginator;

    /**
     * @throws CategoryServiceException
     */
    public function getPaginatedCategories(GetPaginatedCategoriesDto $categoriesDto): LengthAwarePaginator;
}
