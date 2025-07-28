<?php

namespace Tests\Feature\Categories;

use App\Enum\UserRolesEnum;
use Tests\TestCase;

class AddCategoriesTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_super_admin_can_add_category(): void
    {
        $user = $this->createUser(UserRolesEnum::SUPER_ADMIN);

        $this->actingAs($user, 'sanctum');

        $request = $this->postJson('/api/category', [
            'name' => 'Paper',
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Paper',
        ]);

    }

    public function test_sales_rep_cannot_add_category(): void
    {
        $user = $this->createUser(UserRolesEnum::SALES_REP);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/category', [
            'name' => 'Paper',
        ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('categories', [
            'name' => 'Paper',
        ]);

    }

    public function test_admin_can_add_category(): void
    {
        $user = $this->createUser(UserRolesEnum::ADMIN);

        $this->actingAs($user, 'sanctum');

        $request = $this->postJson('/api/category', [
            'name' => 'Paper',
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Paper',
        ]);

    }

    public function test_cannot_add_empty_category_name(): void
    {
        $user = $this->createUser(UserRolesEnum::SUPER_ADMIN);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/category', [
            'name' => '',
        ]);

        $response->assertStatus(422);

    }
}
