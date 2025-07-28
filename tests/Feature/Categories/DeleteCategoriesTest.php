<?php

namespace Tests\Feature\Categories;

use App\Enum\UserRolesEnum;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteCategoriesTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_with_delete_permission_can_delete_category(): void
    {
        $user = $this->createUser(UserRolesEnum::SUPER_ADMIN);
        $category = Category::factory()->create();

        $this->actingAs($user, "sanctum");

        $response = $this->deleteJson('api/category/' . $category->slug);

        $response->assertStatus(200);
    }

    public function test_user_without_delete_permission_cannot_delete_category(): void
    {
        $user = $this->createUser(UserRolesEnum::SALES_REP);
        $category = Category::factory()->create();

        $this->actingAs($user, "sanctum");

        $response = $this->deleteJson('api/category/' . $category->slug);

        $response->assertForbidden();
    }

    public function test_user_cannot_delete_invalid_category(): void
    {
        $user = $this->createUser(UserRolesEnum::SUPER_ADMIN);

        $this->actingAs($user, "sanctum");

        $response = $this->deleteJson('api/category/new');

        $response->assertNotFound();
    }
}
