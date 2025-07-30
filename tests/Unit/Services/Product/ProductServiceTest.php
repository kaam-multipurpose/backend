<?php

namespace Services\Product;

use App\Dto\CreateProductDto;
use App\Enum\ProductUnitsEnum;
use App\Enum\ProductVariantsTypeEnum;
use App\Exceptions\ProductServiceException;
use App\Models\Category;
use App\Models\Product;
use App\Services\Contracts\ProductServiceContract;
use Illuminate\Contracts\Container\BindingResolutionException;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     * @throws BindingResolutionException
     * @throws ProductServiceException
     */
    public function test_system_can_add_product(): void
    {
        $category = Category::factory()->create();
        $data = [
            'name' => 'new product name',
            'category_id' => $category->id,
            'variant_type' => ProductVariantsTypeEnum::COLOR->value,
            'has_variants' => true,
            "units" => [
                [
                    'name' => ProductUnitsEnum::PIECE->value,
                    'conversion_rate' => 1,
                    'percentage' => 25,
                    'is_base' => true,
                    'is_max' => false,
                ],
                [
                    'name' => ProductUnitsEnum::DOZEN->value,
                    'conversion_rate' => 12,
                    'percentage' => 12,
                    'is_base' => false,
                    'is_max' => false,
                ],
                [
                    'name' => ProductUnitsEnum::PACKET->value,
                    'conversion_rate' => 36,
                    'percentage' => 15,
                    'is_base' => false,
                    'is_max' => true,
                ],
            ],
            "variants" => [
                [
                    'name' => 'Red Medium',
                    'cost_price' => 32000,
                ],
                [
                    'name' => 'Red Large',
                    'cost_price' => 42000,
                ],
            ],
        ];

        $createProductDto = CreateProductDto::fromValidated($data);

        $productService = app()->make(ProductServiceContract::class);

        $response = $productService->createProduct($createProductDto);

        $this->assertInstanceOf(Product::class, $response);
        $this->assertDatabaseHas("products", $createProductDto->toArray());
        $this->assertNotEmpty($response->slug);
    }

    /**
     * @throws ProductServiceException
     * @throws BindingResolutionException
     */
    public function test_system_can_create_product_without_variants()
    {
        $category = Category::factory()->create();
        $data = [
            'name' => 'new product name',
            'category_id' => $category->id,
            'cost_price' => 40000,
            'has_variants' => false,
            "units" => [
                [
                    'name' => ProductUnitsEnum::PIECE->value,
                    'conversion_rate' => 1,
                    'percentage' => 25,
                    'is_base' => true,
                    'is_max' => false,
                ],
                [
                    'name' => ProductUnitsEnum::DOZEN->value,
                    'conversion_rate' => 12,
                    'percentage' => 12,
                    'is_base' => false,
                    'is_max' => true,
                ],
            ],
        ];

        $createProductDto = CreateProductDto::fromValidated($data);

        $productService = app()->make(ProductServiceContract::class);

        $response = $productService->createProduct($createProductDto);

        $this->assertInstanceOf(Product::class, $response);
        $this->assertDatabaseHas("products", $createProductDto->toArray());
        $this->assertNotEmpty($response->slug);
        $this->assertNotEmpty($response->variants);
    }
}
