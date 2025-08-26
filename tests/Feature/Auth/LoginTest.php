<?php

use App\Enum\UserRolesEnum;

test('user can login', function () {
    $response = $this->postJson('/api/login', [
        'email' => $this->superAdminUser->email,
        'password' => TEST_USER_PASSWORD,
    ]);

    $response->assertJson([
        'success' => true,
        'message' => 'Logged in successfully.',
    ]);
    $this->assertDatabaseHas('personal_access_tokens', [
        'tokenable_id' => $this->superAdminUser->id,
    ]);
    $this->assertDatabaseHas('refresh_tokens', [
        'user_id' => $this->superAdminUser->id,
    ]);
});

test('user cannot login with incorrect details', function () {
    $response = $this->postJson('/api/login', loginPayload());

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['global']);

    $this->assertGuest();
});

test('login fails if email is missing', function () {
    $response = $this->postJson('/api/login', [
        'password' => TEST_USER_PASSWORD,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('login fails if password is missing', function () {
    $user = $this->createUser(UserRolesEnum::SUPER_ADMIN);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

function loginPayload(): array
{
    return [
        'email' => fake()->safeEmail(),
        'password' => TEST_USER_PASSWORD,
    ];
}
