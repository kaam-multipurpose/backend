<?php

namespace Tests\Feature\Auth;

use App\Enum\UserRolesEnum;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_user_can_login(): void
    {
        $user = $this->createUser(UserRolesEnum::SUPER_ADMIN, [
            'email' => $this->faker->unique()->safeEmail(),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => TEST_USER_PASSWORD,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Logged in successfully.',
        ]);
    }

    public function test_user_cannot_login_with_non_existent_email(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => TEST_USER_PASSWORD,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['global']);
    }

    public function test_user_cannot_login_with_incorrect_password(): void
    {
        $user = $this->createUser(UserRolesEnum::SUPER_ADMIN, [
            'email' => $this->faker->unique()->safeEmail,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['global']);

        $this->assertGuest();
    }

    public function test_login_fails_if_email_is_missing(): void
    {
        $response = $this->postJson('/api/login', [
            'password' => TEST_USER_PASSWORD,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_login_fails_if_password_is_missing(): void
    {
        $user = $this->createUser(UserRolesEnum::SUPER_ADMIN, [
            'email' => $this->faker->unique()->safeEmail,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }
}
