<?php

namespace Categories;

use App\Enum\UserRolesEnum;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateCategoriesTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_with_update_permission_can_update_category(): void
    {
        $user = $this->createUser(UserRolesEnum::SUPER_ADMIN);
        $category = Category::factory()->create();

        $this->actingAs($user, "sanctum");

        $response = $this->patchJson('api/category/' . $category->slug, [
            "name" => "new name",
        ]);

        $this->assertDatabaseHas("categories", [
            "name" => "new name",
        ]);

        $response->assertStatus(200);
    }

    public function test_user_without_update_permission_cannot_update_category(): void
    {
        $user = $this->createUser(UserRolesEnum::SALES_REP);
        $category = Category::factory()->create();

        $this->actingAs($user, "sanctum");

        $response = $this->patchJson('api/category/' . $category->slug, [
            "name" => "new name",
        ]);

        $this->assertDatabaseHas("categories", [
            "name" => $category->name,
        ]);

        $response->assertForbidden();
    }

    public function test_user_cannot_update_invalid_category(): void
    {
        $user = $this->createUser(UserRolesEnum::SUPER_ADMIN);

        $this->actingAs($user, "sanctum");

        $response = $this->patchJson('api/category/new', [
            "name" => "new name",
        ]);

        $response->assertNotFound();
    }
}
