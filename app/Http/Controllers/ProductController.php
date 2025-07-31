<?php

namespace App\Http\Controllers;

use App\Dto\CreateProductDto;
use App\Exceptions\ProductServiceException;
use App\Http\Requests\CreateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\Contracts\ProductServiceContract;
use App\Utils\Logger\Contract\LoggerContract;
use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    use HasAuthenticatedUser, HasLogger;

    public function __construct(
        protected ProductServiceContract $productService,
        protected LoggerContract         $logger
    )
    {
    }

    /**
     * @throws ProductServiceException
     */
    public function createProduct(CreateProductRequest $createProductRequest): JsonResponse
    {
        $product = $this->productService->createProduct(CreateProductDto::fromValidated($createProductRequest->validated()));

        $this->log("info", "Product created Successfully");

        return ApiResponse::success(
            new ProductResource($product, true),
            "Product created successfully.",
            201
        );
    }
}
