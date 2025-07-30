<?php

namespace Services\Product\Variants;

use App\Exceptions\VariantUnitPriceServiceException;
use App\Services\Contracts\VariantUnitPriceServiceContract;
use App\Utils\Seeders\ProductSeederUtil;
use Illuminate\Contracts\Container\BindingResolutionException;
use Tests\TestCase;

class VariantUnitPriceServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     * @throws BindingResolutionException
     * @throws VariantUnitPriceServiceException
     */
    public function test_system_can_set_price_for_variant(): void
    {
        $product = ProductSeederUtil::run()->first();

        $priceService = app()->make(VariantUnitPriceServiceContract::class);
        $response = $priceService->createPrice($product->load(['variants', "units"]));

        $this->assertTrue($response);

        $variant = $product->variants->first();
        $unit = $product->units->first();

        $this->assertDatabaseHas('variant_unit_prices', [
            'variant_id' => $variant->id,
            'product_unit_id' => $unit->id,
        ]);

    }
}
