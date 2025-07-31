<?php

namespace Tests\Feature\Categories;

use App\Enum\UserRolesEnum;
use App\Models\Category;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DeleteCategoriesTest extends TestCase
{
    public function test_user_with_delete_permission_can_delete_category(): void
    {
        $category = Category::factory()->create();

        $this->deleteCategoryAs(UserRolesEnum::SUPER_ADMIN, $category->slug)
            ->assertStatus(200);

        $this->assertSoftDeleted('categories', ['slug' => $category->slug]);
    }

    protected function deleteCategoryAs(UserRolesEnum $role, string $slug): TestResponse
    {
        $user = $this->createUser($role);
        $this->actingAs($user, 'sanctum');

        return $this->deleteJson("/api/category/{$slug}");
    }

    public function test_user_without_delete_permission_cannot_delete_category(): void
    {
        $category = Category::factory()->create();

        $this->deleteCategoryAs(UserRolesEnum::SALES_REP, $category->slug)
            ->assertForbidden();

        $this->assertDatabaseHas('categories', ['slug' => $category->slug]);
    }

    public function test_user_cannot_delete_non_existent_category(): void
    {
        $this->deleteCategoryAs(UserRolesEnum::SUPER_ADMIN, 'non-existent-slug')
            ->assertNotFound();
    }
}
