<?php

namespace App\Utils\Seeders;

use App\Enum\ProductUnitsEnum;
use App\Enum\ProductVariantsTypeEnum;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Variant;
use App\Models\VariantUnitPrice;
use Illuminate\Database\Eloquent\Collection;

class ProductSeederUtil
{
    public static function run(int $count = 1, bool $withPrice = false): Collection
    {
        return Product::factory()->count($count)->create()
            ->each(function (Product $product) use ($withPrice) {

                $unitCounts = rand(2, count(ProductVariantsTypeEnum::values()));
                $usedNames = [];

                Variant::factory()->count(rand(1, 4))->create([
                    "product_id" => $product->id
                ]);

                for ($i = 0; $i < $unitCounts; $i++) {
                    $availableNames = array_diff(ProductUnitsEnum::values(), $usedNames);
                    $unitName = fake()->randomElement($availableNames);
                    $usedNames[] = $unitName;

                    $isMax = $i >= $unitCounts - 1;
                    $isBase = $i == 0;
                    ProductUnit::factory()->create([
                        "name" => $unitName,
                        "product_id" => $product->id,
                        "is_base" => $isBase,
                        "is_max" => $isMax,
                    ]);
                }

                if ($withPrice) {
                    $variants = $product->variants();
                    $units = $product->units();

                    $variants->each(function ($variant) use ($units) {
                        $units->each(function ($unit) use ($variant) {
                            $sellingPrice = ($variant->cost_price * $unit->conversion_rate) * $unit->multiplier;
                            VariantUnitPrice::factory()->create([
                                'variant_id' => $variant->id,
                                'product_unit_id' => $unit->id,
                                "selling_price" => $sellingPrice,
                            ]);
                        });
                    });
                }
            });
    }
}
