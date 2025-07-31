<?php

namespace Tests\Feature\Categories;

use App\Enum\UserRolesEnum;
use App\Models\Category;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class UpdateCategoriesTest extends TestCase
{
    public function test_user_with_update_permission_can_update_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->updateCategoryAs(UserRolesEnum::SUPER_ADMIN, $category->slug);

        $response->assertStatus(200);
        $this->assertDatabaseHas('categories', ['name' => 'new name']);
    }

    protected function updateCategoryAs(UserRolesEnum $role, string $slug, array $data = []): TestResponse
    {
        $user = $this->createUser($role);
        $this->actingAs($user, 'sanctum');

        return $this->patchJson("/api/category/{$slug}", array_merge([
            'name' => 'new name',
        ], $data));
    }

    public function test_user_without_update_permission_cannot_update_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->updateCategoryAs(UserRolesEnum::SALES_REP, $category->slug);

        $response->assertForbidden();
        $this->assertDatabaseHas('categories', ['name' => $category->name]);
    }

    public function test_user_cannot_update_non_existent_category(): void
    {
        $response = $this->updateCategoryAs(UserRolesEnum::SUPER_ADMIN, 'invalid-slug');

        $response->assertNotFound();
    }
}
