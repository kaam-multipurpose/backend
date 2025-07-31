<?php

namespace Tests\Feature\Categories;

use App\Enum\UserRolesEnum;
use Tests\TestCase;

class AddCategoriesTest extends TestCase
{
    protected string $categoryName = 'Paper';

    public function test_super_admin_can_add_category(): void
    {
        $this->postCategory(UserRolesEnum::SUPER_ADMIN)
            ->assertStatus(201);

        $this->assertDatabaseHas('categories', ['name' => $this->categoryName]);
    }

    protected function postCategory(UserRolesEnum $role, array $data = []): \Illuminate\Testing\TestResponse
    {
        $user = $this->createUser($role);
        $this->actingAs($user, 'sanctum');

        return $this->postJson('/api/category', array_merge([
            'name' => $this->categoryName,
        ], $data));
    }

    public function test_sales_rep_cannot_add_category(): void
    {
        $this->postCategory(UserRolesEnum::SALES_REP)
            ->assertForbidden();

        $this->assertDatabaseMissing('categories', ['name' => $this->categoryName]);
    }

    public function test_admin_can_add_category(): void
    {
        $this->postCategory(UserRolesEnum::ADMIN)
            ->assertStatus(201);

        $this->assertDatabaseHas('categories', ['name' => $this->categoryName]);
    }

    public function test_cannot_add_empty_category_name(): void
    {
        $response = $this->postCategory(UserRolesEnum::SUPER_ADMIN, [
            'name' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }
}
