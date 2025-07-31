<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductUnit>
 */
class ProductUnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "unit_id" => Unit::inRandomOrder()->first() ?? Unit::factory()->create(),
            "product_id" => Product::inRandomOrder()->first() ?? Product::factory()->create(),
            "is_base" => fake()->boolean,
            "is_max" => fake()->boolean,
            "conversion_rate" => fake()->numberBetween(1, 100),
            "multiplier" => fake()->randomFloat(2, 0.1, 10.0),
        ];
    }
}
