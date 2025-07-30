<?php

namespace App\Services;

use App\Exceptions\VariantServiceException;
use App\Models\Product;
use App\Models\VariantUnitPrice;
use App\Services\Contracts\VariantUnitPriceServiceContract;
use App\Utils\Logger\Contract\LoggerContract;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;

class VariantUnitPriceService implements VariantUnitPriceServiceContract
{
    use HasAuthenticatedUser, HasLogger;

    public function __construct(
        protected LoggerContract $logger,
    )
    {
    }

    /**
     * @throws VariantServiceException
     */
    public function createPrice(Product $product): bool
    {
        try {
            $this->log("info", "creating product variant price", [
                "product_id" => $product->id,
            ]);
            $variants = $product->variants;
            $units = $product->units;


            $toSave = $variants->flatMap(function ($variant) use ($units) {
                return $units->map(function ($unit) use ($variant) {
                    $sellingPrice = ($variant->cost_price * $unit->conversion_rate) * $unit->multiplier;
                    return [
                        'variant_id' => $variant->id,
                        "product_unit_id" => $unit->id,
                        "selling_price" => $sellingPrice,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ];
                });
            })->toArray();
            return VariantUnitPrice::insert($toSave);

        } catch (\Throwable $e) {
            $this->log("error", "Failed to create product variant price", [
                "product_id" => $product->id,
            ]);
            throw new VariantServiceException("Failed to create product price", previous: $e);
        }
    }
}
