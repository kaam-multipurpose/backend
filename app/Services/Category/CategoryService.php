<?php

declare(strict_types=1);

namespace App\Services\Category;

use App\Dtos\AddCategoryDto;
use App\Dtos\GetPaginatedCategoriesDto;
use App\Exceptions\CategoryServiceException;
use App\Models\Category;
use App\Services\Contracts\CategoryServiceContract;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;

final class CategoryService implements CategoryServiceContract
{
    use HasAuthenticatedUser;
    use HasLogger;

    /**
     * @throws CategoryServiceException
     * @throws Throwable
     */
    public function addCategory(AddCategoryDto $dto, ?Category $parentCategory = null): Category
    {
        try {
            self::logInfo('Attempt to add category');

            if ($dto->isSubcategory && ! $parentCategory) {
                throw new CategoryServiceException('Subcategory creation requires a parent category.');
            }

            return DB::transaction(function () use ($dto, $parentCategory) {
                $category = $dto->isSubcategory
                    ? $parentCategory->subCategories()->create($dto->toArray())
                    : Category::query()->create($dto->toArray());

                if (! $dto->isSubcategory || $dto->hasAdditionalVariantType) {
                    $this->syncVariantType($category, $dto);
                }

                return $category;
            });

        } catch (Throwable $throwable) {
            if ($throwable instanceof CategoryServiceException) {
                throw $throwable;
            }

            self::logException($throwable, 'Caught Exception when adding category');
            throw new CategoryServiceException('Unable to add category');
        }
    }

    /**
     * @throws CategoryServiceException
     */
    public function getCategories(GetPaginatedCategoriesDto $dto): LengthAwarePaginator
    {
        try {
            self::logInfo('Attempt to get categories');

            return Category::categories()->paginate(
                perPage: $dto->row,
                page: $dto->page
            );

        } catch (Throwable $throwable) {
            self::logException($throwable, 'Caught Exception when getting categories');
            throw new CategoryServiceException('Unable to get categories');
        }
    }

    /**
     * @throws CategoryServiceException
     */
    public function getCategory(Category $category): Category
    {
        try {
            self::logInfo('Attempt to get category');

            return $category;

        } catch (Throwable $throwable) {
            self::logException($throwable, 'Caught Exception when getting category');
            throw new CategoryServiceException('Unable to get category');
        }
    }

    private function syncVariantType(Category $category, AddCategoryDto $dto): void
    {
        $variantTypeIds = $dto->variantTypeIds;

        if ($dto->isSubcategory) {
            $parentVariantIds = $category->category?->variantTypes->pluck('id')->toArray();
            $variantTypeIds = array_filter(
                $variantTypeIds,
                fn ($variantTypeId): bool => ! in_array($variantTypeId, $parentVariantIds)
            );
        }

        $category->variantTypes()->sync($variantTypeIds);
    }
}
