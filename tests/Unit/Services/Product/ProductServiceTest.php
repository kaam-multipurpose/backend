<?php

namespace Tests\Unit\Services\Product;

use App\Dto\CreateProductDto;
use App\Enum\ProductVariantsTypeEnum;
use App\Exceptions\ProductServiceException;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use App\Services\Contracts\ProductServiceContract;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    protected ProductServiceContract $service;

    /**
     * @throws ProductServiceException
     */
    public function test_it_can_add_product_with_variants(): void
    {
        $category = Category::factory()->create();
        $unit = Unit::factory()->create();

        $dto = CreateProductDto::fromValidated([
            'name' => 'new product name',
            'category_id' => $category->id,
            'variant_type' => ProductVariantsTypeEnum::COLOR->value,
            'has_variants' => true,
            'units' => [
                [
                    'unit_id' => $unit->id,
                    'conversion_rate' => 1,
                    'percentage' => 25,
                    'is_base' => true,
                    'is_max' => false,
                ],
            ],
            'variants' => [
                ['name' => 'Red Medium', 'cost_price' => 32000],
                ['name' => 'Red Large', 'cost_price' => 42000],
            ],
        ]);

        $product = $this->service->createProduct($dto);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertNotEmpty($product->slug);
        $this->assertDatabaseHas('products', ['name' => $dto->name]);
    }

    /**
     * @throws ProductServiceException
     */
    public function test_it_can_create_product_without_variants(): void
    {
        $category = Category::factory()->create();
        $unit = Unit::factory()->create();

        $dto = CreateProductDto::fromValidated([
            'name' => 'new product name',
            'category_id' => $category->id,
            'cost_price' => 40000,
            'has_variants' => false,
            'units' => [
                [
                    'unit_id' => $unit->id,
                    'conversion_rate' => 1,
                    'percentage' => 25,
                    'is_base' => true,
                    'is_max' => false,
                ],
            ],
        ]);

        $product = $this->service->createProduct($dto);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertNotEmpty($product->slug);
        $this->assertNotEmpty($product->variants); // Likely returns base variant
        $this->assertDatabaseHas('products', ['name' => $dto->name]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = $this->app->make(ProductServiceContract::class);
    }
}
