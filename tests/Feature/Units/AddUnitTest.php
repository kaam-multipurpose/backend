<?php

namespace Tests\Feature\Units;

use App\Enum\UserRolesEnum;
use Tests\TestCase;

class AddUnitTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_with_add_unit_permission_can_add_unit(): void
    {
        $user = $this->createUser(UserRolesEnum::SUPER_ADMIN);
        $this->actingAs($user)
            ->postJson('/api/unit', [
                'name' => fake()->word()
            ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'name']
            ]);
    }
}
