<?php

declare(strict_types=1);

use App\Models\Category;
use Symfony\Component\HttpFoundation\Response;

describe('Get Categories', function (): void {
    beforeEach(function (): void {
        $this->categories = Category::factory()->count(15)->create();
    });

    describe('Authorization', function (): void {
        it('allows super admin to list categories', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->getJson('/api/categories');

            $response->assertOk()
                ->assertJsonStructure([
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'name',
                            ],
                        ],
                        'links',
                        'meta',
                    ],
                    'message',
                ]);
        });

        it('allows admin to list categories', function (): void {
            $this->actingAs($this->adminUser, 'sanctum');

            $response = $this->getJson('/api/categories');

            $response->assertOk()
                ->assertJsonPath('data.meta.total', 15)
                ->assertJsonCount(5, 'data.data'); // Default pagination
        });

        it('allows sales rep to list categories', function (): void {
            $this->actingAs($this->salesRepUser, 'sanctum');

            $response = $this->getJson('/api/categories');

            $response->assertOk()
                ->assertJsonPath('data.meta.total', 15);
        });

        it('prevents unauthenticated access', function (): void {
            $response = $this->getJson('/api/categories');

            $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        });
    });

    describe('Pagination', function (): void {
        it('paginates results correctly', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->getJson('/api/categories?page=2&row=5');

            $response->assertOk()
                ->assertJsonPath('data.meta.current_page', 2)
                ->assertJsonPath('data.meta.per_page', 5)
                ->assertJsonCount(5, 'data.data');
        });

        it('validates pagination parameters', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->getJson('/api/categories?page=abc&row=def');

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->assertJsonValidationErrors(['page', 'row']);
        });
    });
});
