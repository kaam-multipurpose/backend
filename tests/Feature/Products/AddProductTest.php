<?php

namespace Tests\Feature\Products;

use App\Enum\UserRolesEnum;
use App\Models\Category;
use App\Models\Unit;
use Tests\TestCase;

class AddProductTest extends TestCase
{
    public function test_super_admin_can_add_product(): void
    {
        $user = $this->createUser(UserRolesEnum::SUPER_ADMIN);
        $category = Category::factory()->create();
        $units = Unit::factory()->count(2)->create();

        $payload = $this->buildProductPayload($category->id, $units->first()->id, $units->last()->id);

        $this->actingAs($user, 'sanctum');
        $response = $this->postJson('/api/product', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Choco Delight']);
    }

    protected function buildProductPayload(int $categoryId, int $unitBaseId, int $unitMaxId): array
    {
        return [
            'name' => 'Choco Delight',
            'slug' => 'choco-delight',
            'category_id' => $categoryId,
            // 'variant_type' => ProductVariantsTypeEnum::SIZE->value, // Uncomment if applicable
            'cost_price' => 40000,
            'has_variants' => false,
            'units' => [
                [
                    'unit_id' => $unitBaseId,
                    'multiplier' => 1,
                    'conversion_rate' => 1,
                    'percentage' => 25,
                    'is_base' => true,
                    'is_max' => false,
                ],
                [
                    'unit_id' => $unitMaxId,
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
            ],
        ];
    }
}
