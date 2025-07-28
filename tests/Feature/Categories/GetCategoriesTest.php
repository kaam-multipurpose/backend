<?php

namespace Tests\Feature\Categories;

use App\Enum\UserRolesEnum;
use Tests\TestCase;

class GetCategoriesTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_super_admin_get_all_categories(): void
    {
        $user = $this->createUser(UserRolesEnum::SUPER_ADMIN);

        $this->actingAs($user, 'sanctum');

        $response = $this->get('/api/category');

        $response->assertStatus(200);
    }

    public function test_super_admin_get_all_categories_paginated(): void
    {
        $user = $this->createUser(UserRolesEnum::SUPER_ADMIN);

        $this->actingAs($user, 'sanctum');

        $response = $this->get('/api/category/paginated');

        $response->assertStatus(200);
    }
}
