<?php

use App\Models\Category;
use Symfony\Component\HttpFoundation\Response;

describe('Get Category', function (): void {
    beforeEach(function (): void {
        // Create a category with variant types and subcategories
        $this->category = Category::factory()
            ->hasVariantTypes(3)
            ->hasSubCategories(2)
            ->create();
    });

    describe('Authorization', function (): void {
        it('allows super admin to view a category', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->getJson("/api/categories/{$this->category->slug}");

            $response->assertOk()
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'name',
                        'slug',
                        'variant_type_count',
                        'sub_category_count',
                        'variant_types',
                        'sub_categories',
                    ],
                    'message',
                ])
                ->assertJsonPath('message', 'Category retrieved')
                ->assertJsonPath('data.id', $this->category->id)
                ->assertJsonPath('data.name', $this->category->name)
                ->assertJsonPath('data.variant_type_count', 3)
                ->assertJsonPath('data.sub_category_count', 2);
        });

        it('allows admin to view a category', function (): void {
            $this->actingAs($this->adminUser, 'sanctum');

            $response = $this->getJson("/api/categories/{$this->category->slug}");

            $response->assertOk()
                ->assertJsonPath('data.id', $this->category->id);
        });

        it('allows sales rep to view a category', function (): void {
            $this->actingAs($this->salesRepUser, 'sanctum');

            $response = $this->getJson("/api/categories/{$this->category->slug}");

            $response->assertOk()
                ->assertJsonPath('data.id', $this->category->id);
        });

        it('prevents unauthenticated access', function (): void {
            $response = $this->getJson("/api/categories/{$this->category->slug}");

            $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        });
    });

    describe('Route Parameter', function (): void {
        it('returns 404 for non-existent category', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->getJson('/api/categories/non-existent-category');

            $response->assertNotFound();
        });

        it('uses slug for category lookup', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->getJson("/api/categories/{$this->category->id}");

            $response->assertNotFound();
        });
    });

    describe('Response Structure', function (): void {
        it('includes variant types and subcategories in expanded view', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->getJson("/api/categories/{$this->category->slug}");

            $response->assertOk()
                ->assertJsonStructure([
                    'data' => [
                        'variant_types' => [
                            '*' => [
                                'id',
                                'name',
                                'values',
                            ],
                        ],
                        'sub_categories' => [
                            '*' => [
                                'id',
                                'name',
                                'slug',
                            ],
                        ],
                    ],
                ]);
        });

        it('shows correct parent category for subcategories', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $subcategory = $this->category->subCategories->first();

            $response = $this->getJson("/api/categories/{$subcategory->slug}");

            $response->assertOk()
                ->assertJsonPath('data.parent_category', $this->category->name);
        });
    });
});
