<?php

namespace App\Services;


use App\Dto\CreateProductUnitDto;
use App\Exceptions\ProductUnitServiceException;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Services\Contracts\ProductUnitServiceContract;
use App\Utils\Logger\Contract\LoggerContract;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;

class ProductUnitService implements ProductUnitServiceContract
{
    use HasAuthenticatedUser, HasLogger;

    public function __construct(
        protected LoggerContract $logger,
    )
    {
    }

    /**
     * @param array<CreateProductUnitDto> $createProductUnitDto
     * @param Product $product
     * @return bool
     * @throws ProductUnitServiceException
     */
    public function createProductUnits(array $createProductUnitDto, Product $product): bool
    {
        try {
            $units = array_map(function (CreateProductUnitDto $item) use ($product) {
                return array_merge($item->toArray(), [
                    'product_id' => $product->id,
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
            }, $createProductUnitDto);
            return ProductUnit::insert($units);
        } catch (\Throwable $e) {
            $this->log("error", "Failed to create product units", [
                "product_id" => $product->id,
            ]);
            throw new ProductUnitServiceException("Failed to create product units", previous: $e);
        }
    }
}
