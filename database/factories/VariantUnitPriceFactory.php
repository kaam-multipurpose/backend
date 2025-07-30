<?php

namespace Database\Factories;

use App\Models\ProductUnit;
use App\Models\Variant;
use App\Models\VariantUnitPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VariantUnitPrice>
 */
class VariantUnitPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "variant_id" => Variant::inRandomOrder()->first() ?? Variant::factory()->create(),
            "product_unit_id" => ProductUnit::inRandomOrder()->first() ?? ProductUnit::factory()->create(),
            "selling_price" => fake()->numberBetween(5000, 15000),
        ];
    }
}
