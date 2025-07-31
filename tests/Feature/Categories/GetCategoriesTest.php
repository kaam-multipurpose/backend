<?php

namespace Tests\Feature\Categories;

use App\Enum\UserRolesEnum;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class GetCategoriesTest extends TestCase
{
    public function test_super_admin_can_get_all_categories(): void
    {
        $this->getCategories('')
            ->assertStatus(200);
    }

    protected function getCategories(string $endpoint, UserRolesEnum $role = UserRolesEnum::SUPER_ADMIN): TestResponse
    {
        $user = $this->createUser($role);
        $this->actingAs($user, 'sanctum');

        return $this->get("/api/category{$endpoint}");
    }

    public function test_super_admin_can_get_paginated_categories(): void
    {
        $this->getCategories('/paginated')
            ->assertStatus(200);
    }
}
