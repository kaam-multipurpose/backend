<?php

namespace Tests\Feature\Auth;

use App\Enum\UserRolesEnum;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_user_can_login(): void
    {
        $user = $this->createUser(UserRolesEnum::SUPER_ADMIN);

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

    public function test_user_cannot_login_with_incorrect_details(): void
    {
        $response = $this->postJson('/api/login', $this->LoginPayload());

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['global']);

        $this->assertGuest();
    }

    protected function LoginPayload(): array
    {
        return [
            'email' => fake()->safeEmail(),
            'password' => TEST_USER_PASSWORD,
        ];
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
        $user = $this->createUser(UserRolesEnum::SUPER_ADMIN);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }
}
