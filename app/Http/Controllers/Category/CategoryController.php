<?php

declare(strict_types=1);

namespace App\Http\Controllers\Category;

use App\Dtos\AddCategoryDto;
use App\Dtos\GetPaginatedCategoriesDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddCategoryRequest;
use App\Http\Requests\AddSubCategoryRequest;
use App\Http\Requests\GetCategoriesRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\Contracts\CategoryServiceContract;
use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class CategoryController extends Controller
{
    public function __construct(
        private CategoryServiceContract $categoryService,
    ) {
    }

    public function addSubCategory(AddSubCategoryRequest $request, Category $category): \Illuminate\Http\JsonResponse
    {
        $newCategory = $this->categoryService->addCategory(
            AddCategoryDto::fromValidated($request->validated())->isSubCategory(),
            $category
        );

        self::logInfo('Category added successfully');

        return ApiResponse::success(
            new CategoryResource($newCategory),
            message: 'Category added successfully',
            status: Response::HTTP_CREATED
        );
    }


    public function addCategory(AddCategoryRequest $request): \Illuminate\Http\JsonResponse
    {
        $newCategory = $this->categoryService->addCategory(
            AddCategoryDto::fromValidated($request->validated()),
        );

        self::logInfo('Category added successfully');

        return ApiResponse::success(
            new CategoryResource($newCategory),
            message: 'Category added successfully',
            status: Response::HTTP_CREATED
        );
    }

    public function getCategory(Request $request, Category $category): \Illuminate\Http\JsonResponse
    {
        $category = $this->categoryService->getCategory($category);
        $data = new CategoryResource($category)->isExpanded();

        self::logInfo('Category gotten successfully');

        return ApiResponse::success(
            $data,
            message: 'Category retrieved',
        );
    }

    public function getCategories(GetCategoriesRequest $request): \Illuminate\Http\JsonResponse
    {
        $categories = $this->categoryService->getCategories(
            GetPaginatedCategoriesDto::fromValidated($request->validated()),
        );

        self::logInfo('Category gotten successfully');

        return ApiResponse::success(
            CategoryResource::collection($categories),
            'Category retrieved successfully',
        );

    }
}
