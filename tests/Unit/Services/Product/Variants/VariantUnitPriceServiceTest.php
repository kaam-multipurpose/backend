<?php

namespace Tests\Unit\Services\Product\Variants;

use App\Exceptions\VariantUnitPriceServiceException;
use App\Models\Product;
use App\Services\Contracts\VariantUnitPriceServiceContract;
use App\Utils\Seeders\ProductSeederUtil;
use Illuminate\Contracts\Container\BindingResolutionException;
use Tests\TestCase;

class VariantUnitPriceServiceTest extends TestCase
{
    protected VariantUnitPriceServiceContract $priceService;

    /**
     * @throws BindingResolutionException
     * @throws VariantUnitPriceServiceException
     */
    public function test_it_can_set_prices_for_product_variants(): void
    {
        $product = ProductSeederUtil::run()->first()?->load(['variants', 'productUnits']);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertNotEmpty($product->variants);
        $this->assertNotEmpty($product->productUnits);

        $result = $this->priceService->createPrice($product);

        $this->assertTrue($result);

        $variant = $product->variants->first();
        $unit = $product->productUnits->first();

        $this->assertDatabaseHas('variant_unit_prices', [
            'variant_id' => $variant->id,
            'product_unit_id' => $unit->id,
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->priceService = $this->app->make(VariantUnitPriceServiceContract::class);
    }
}
