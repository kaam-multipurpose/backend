<?php

namespace App\Services;

use App\Dto\CreateCategoryDto;
use App\Dto\GetPaginatedCategoriesDto;
use App\Exceptions\CategoryServiceException;
use App\Models\Category;
use App\Services\Contracts\CategoryServiceContract;
use App\Utils\Logger\Contract\LoggerContract;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class CategoryService implements CategoryServiceContract
{
    use HasAuthenticatedUser, HasLogger;

    public function __construct(
        protected LoggerContract $logger
    )
    {
    }

    /**
     * @throws CategoryServiceException()
     */
    public function createCategory(CreateCategoryDto $createCategoryDto): Category
    {
        try {
            $this->log("info", "Creating a Category");

            return Category::create($createCategoryDto->toArray());
        } catch (\Throwable $e) {

            $this->log("warning", "Failed to create a category");
            throw new CategoryServiceException('Unable to create category.', previous: $e);
        }
    }

    /**
     * @throws CategoryServiceException
     */
    public function updateCategory(Category $category, CreateCategoryDto $createCategoryDto): Category
    {
        try {
            $this->log("info", "Updating a Category", [
                "category_id" => $category->id
            ]);

            if (!empty($createCategoryDto->name)) {
                $category->name = $createCategoryDto->name;
                $category->slug = Str::slug($createCategoryDto->name);
                $category->save();
            }

            return $category;
        } catch (\Throwable $e) {

            $this->log("warning", "Failed Updating a Category", [
                "category_id" => $category->id
            ]);

            throw new CategoryServiceException('Unable to update category.', previous: $e);
        }
    }

    /**
     * @throws CategoryServiceException
     */
    public function deleteCategory(Category $category): bool
    {
        try {
            $this->log("info", "Deleting a Category", [
                "category_id" => $category->id
            ]);

            return $category->delete();
        } catch (\Throwable $e) {

            $this->log("warning", "Failed Deleting a Category", [
                "category_id" => $category->id
            ]);

            throw new CategoryServiceException('Unable to delete category.', previous: $e);
        }
    }

    /**
     * @throws CategoryServiceException
     */
    public function getAllCategories(): Collection
    {
        try {
            $this->log("info", "Getting all Categories");
            return Category::all();
        } catch (\Throwable $e) {

            $this->log("warning", "Failed to get all categories");

            throw new CategoryServiceException('Unable to get categories.', previous: $e);
        }
    }

    //    public function getPaginatedCategoryProducts(Category $category): LengthAwarePaginator
    //    {
    //        // TODO: Implement getPaginatedCategoryProducts() method.
    //    }

    /**
     * @throws CategoryServiceException
     */
    public function getPaginatedCategories(GetPaginatedCategoriesDto $categoriesDto): LengthAwarePaginator
    {
        try {
            $this->log("info", "Getting all Categories (Paginated)");
            return Category::paginate(perPage: $categoriesDto->row, page: $categoriesDto->page);
        } catch (\Throwable $e) {

            $this->log("warning", "Failed to get all categories (Paginated)");

            throw new CategoryServiceException('Unable to get categories (Paginated).', previous: $e);
        }
    }
}
