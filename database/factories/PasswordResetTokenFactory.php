<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PasswordResetToken>
 */
class PasswordResetTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->safeEmail,
            'token' => Str::random(6),
            'expires_at' => now()->addHours(3),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state([
            'email' => $user->email,
        ]);
    }
}
