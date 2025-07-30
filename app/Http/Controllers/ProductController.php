<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Services\Contracts\ProductServiceContract;
use App\Utils\Logger\Contract\LoggerContract;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        protected ProductServiceContract $productService,
        protected LoggerContract         $loggerContract
    )
    {
    }

    public function createProduct(CreateProductRequest $createProductRequest): JsonResponse
    {

    }
}
