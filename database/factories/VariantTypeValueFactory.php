<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\VariantType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VariantTypeValue>
 */
final class VariantTypeValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'variant_type_id' => VariantType::factory(),
            'name' => fake()->unique()->word(),
        ];
    }
}
