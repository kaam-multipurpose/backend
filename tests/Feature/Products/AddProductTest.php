<?php

namespace Tests\Feature\Products;

use App\Enum\ProductUnitsEnum;
use App\Enum\UserRolesEnum;
use App\Models\Category;
use Tests\TestCase;

class AddProductTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_with_permission_can_add_product(): void
    {
        $user = $this->createUser(UserRolesEnum::SUPER_ADMIN);
        $category = Category::factory()->create();
        $item = [
            'name' => 'Choco Delight',
            'slug' => 'choco-delight',
            'category_id' => $category->id,
//            'variant_type' => ProductVariantsTypeEnum::SIZE->value,
            'cost_price' => 40000,
            "has_variants" => false,
            'units' => [
                [
                    'name' => ProductUnitsEnum::PIECE->value,
                    'multiplier' => 1,
                    'conversion_rate' => 1,
                    'percentage' => 25,
                    'is_base' => true,
                    'is_max' => false,
                ],
                [
                    'name' => ProductUnitsEnum::DOZEN->value,
                    'multiplier' => 12,
                    'conversion_rate' => 12,
                    'percentage' => 12,
                    'is_base' => false,
                    'is_max' => true,
                ],
            ],
            'variants' => [
                [
                    'name' => 'Mini Pack',
                    'slug' => 'mini-pack',
                    'cost_price' => 1500,
                ],
                [
                    'name' => 'Family Pack',
                    'slug' => 'family-pack',
                    'cost_price' => 5000,
                ],
            ]
        ];

        $this->actingAs($user, "sanctum");
        $response = $this->postJson("/api/product", $item);
        $response->assertStatus(201);
    }
}
