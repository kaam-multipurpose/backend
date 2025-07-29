<?php

namespace App\Services;

use App\Dto\CreateProductDto;
use App\Dto\CreateVariantDto;
use App\Exceptions\ProductServiceException;
use App\Exceptions\VariantServiceException;
use App\Models\Product;
use App\Models\Variant;
use App\Services\Contracts\ProductServiceContract;
use App\Services\Contracts\ProductUnitServiceContract;
use App\Services\Contracts\VariantServiceContract;
use App\Utils\Logger\Contract\LoggerContract;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Support\Facades\DB;

class ProductService implements ProductServiceContract
{
    use HasAuthenticatedUser, HasLogger;

    public function __construct(
        protected LoggerContract             $logger,
        protected VariantServiceContract     $variantService,
        protected ProductUnitServiceContract $productUnitService
    )
    {
    }

    /**
     * @param CreateProductDto $createProductDto
     * @return Product
     * @throws ProductServiceException
     */
    public function createProduct(CreateProductDto $createProductDto): Product
    {
        try {
            DB::beginTransaction();

            $this->log("info", "Creating product");
            $newProduct = Product::create($createProductDto->toArray());

            $this->productUnitService->createProductUnits(
                $createProductDto->units,
                $newProduct
            );

            $this->log("info", "Successfully Created units", [
                "product_id" => $newProduct->id,
            ]);

            if (!empty($createProductDto->variants)) {
                $this->createManyVariants($createProductDto->variants, $newProduct);
                $this->log("info", "Successfully Created bulk variants", [
                    "product_id" => $newProduct->id,
                ]);
            } else {
                $variantDto = new CreateVariantDto(
                    name: $newProduct->name,
                    costPrice: $createProductDto->costPrice,
                );
                $this->createVariant($variantDto, $newProduct);
                $this->log("info", "Successfully Create variants", [
                    "product_id" => $newProduct->id,
                ]);
            }
            DB::commit();
            return $newProduct;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->log("error", "fail to create product");
            throw new ProductServiceException(
                "Unable to create product.",
                $e
            );
        }
    }

    /**
     * @param CreateVariantDto[] $variantsDto
     * @param Product $product
     * @return bool
     * @throws VariantServiceException
     */
    private function createManyVariants(array $variantsDto, Product $product): bool
    {

        return $this->variantService->createManyVariant($variantsDto, $product);
    }

    /**
     * @throws VariantServiceException
     */
    private function createVariant(CreateVariantDto $createVariantDto, Product $product): Variant
    {
        return $this->variantService->createVariant($createVariantDto, $product);
    }
}
