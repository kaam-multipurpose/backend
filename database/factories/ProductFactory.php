<?php

namespace Database\Factories;

use App\Enum\ProductVariantsTypeEnum;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory()->create()->id,
            'variant_type' => fake()->randomElement(ProductVariantsTypeEnum::values()),
        ];
    }
}
