<?php

namespace App\Http\Controllers;

use App\Dto\CreateCategoryDto;
use App\Dto\GetPaginatedCategoriesDto;
use App\Enum\PaginationEnum;
use App\Exceptions\CategoryServiceException;
use App\Http\Resources\CategoryResources;
use App\Models\Category;
use App\Services\Contracts\CategoryServiceContract;
use App\Utils\Logger\Contract\LoggerContract;
use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    use HasAuthenticatedUser, HasLogger;

    public function __construct(
        protected CategoryServiceContract $categoryService,
        protected LoggerContract          $logger
    )
    {
    }

    /**
     * @throws CategoryServiceException
     */
    public function createCategory(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
        ]);

        $dto = CreateCategoryDto::fromValidated($validated);

        $category = $this->categoryService->createCategory($dto);
        $this->log("info", "Successfully Created Category");

        return ApiResponse::success(
            new CategoryResources($category),
            'Category created successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * @throws CategoryServiceException
     */
    public function getAllCategories(): JsonResponse
    {
        $categories = $this->categoryService->getAllCategories();

        $this->log("info", "Successfully gets Category");
        return ApiResponse::success(
            CategoryResources::collection($categories),
            'Category retrieved successfully.'
        );
    }

    /**
     * @throws CategoryServiceException
     */
    public function getAllCategoriesPaginated(): JsonResponse
    {
        $validated = request()->validate(array_merge(
            PaginationEnum::rules()
        ));
        $categories = $this->categoryService->getPaginatedCategories(
            GetPaginatedCategoriesDto::fromValidated($validated)
        );

        $this->log("info", "Successfully gets Categories (Paginated)");
        return ApiResponse::success(
            CategoryResources::collection($categories)->response()->getData(),
            'Category retrieved successfully.'
        );
    }

    /**
     * @throws CategoryServiceException
     */
    public function deleteCategory(Request $request, Category $category): JsonResponse
    {
        $this->categoryService->deleteCategory($category);

        $this->log("info", "Successfully Deleted Category", [
            "category_id" => $category->id
        ]);

        return ApiResponse::success(message: "Successfully Deleted Category");
    }

    /**
     * @throws CategoryServiceException
     */
    public function updateCategory(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'string',
        ]);
        $response = $this->categoryService->updateCategory(
            $category,
            CreateCategoryDto::fromValidated($validated)
        );

        $this->log("info", "Successfully Update Category", [
            "category_id" => $category->id
        ]);

        return ApiResponse::success(
            new CategoryResources($response),
            message: "Successfully Update Category"
        );
    }
}
