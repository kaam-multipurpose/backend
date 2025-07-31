<?php

namespace App\Utils\Seeders;

use App\Enum\ProductVariantsTypeEnum;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Unit;
use App\Models\Variant;
use App\Models\VariantUnitPrice;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class ProductSeederUtil
{
    public static function run(int $categoryCount = 1, int $productCount = 1, bool $withPrice = false): Collection
    {
        $products = collect();
        $units = Unit::factory(6)->create();

        Category::factory($categoryCount)
            ->create()->each(function (Category $category, $count) use ($withPrice, $productCount, $products, $units) {
                self::seedProduct($category, $withPrice, $productCount, $products, $count, $units);
            });

        return $products;
    }

    private static function seedProduct(Category $category, bool $withPrice, int $productCount, Collection $products, $count, $units): void
    {
        $hasVariants = $count % 2 === 0;
        $variantType = $hasVariants
            ? fake()->randomElement(ProductVariantsTypeEnum::values())
            : null;

        Product::factory()->count($productCount)
            ->create([
                "category_id" => $category->id,
                "has_variants" => $hasVariants,
                "variant_type" => $variantType,
            ])->each(function (Product $product) use ($withPrice, $products, $hasVariants, $units) {
                $products->push($product);

                if ($hasVariants == 0) {
                    Variant::factory()->create([
                        "product_id" => $product->id,
                        "name" => $product->name,
                    ]);
                } else {
                    Variant::factory()->count(rand(1, 4))->create([
                        "product_id" => $product->id
                    ]);
                }

                self::seedProductUnits($product, $units);

                if ($withPrice) {
                    self::seedVariantsPrice($product);
                }

            });
    }

    private static function seedProductUnits(Product $product, EloquentCollection $units): void
    {
        $unitCounts = rand(2, $units->count());
        $usedUnitIds = [];

        for ($i = 0; $i < $unitCounts; $i++) {
            $availableUnitIds = $units->pluck('id')->diff($usedUnitIds)->values();
            $useUnitId = $availableUnitIds->random();

            $usedUnitIds[] = $useUnitId;

            $isMax = $i >= $unitCounts - 1;
            $isBase = $i == 0;

            ProductUnit::factory()->create([
                'unit_id' => $useUnitId,
                'product_id' => $product->id,
                'is_base' => $isBase,
                'is_max' => $isMax,
            ]);
        }
    }

    private static function seedVariantsPrice(Product $product): void
    {
        $variants = $product->variants();
        $units = $product->productUnits();

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

}
