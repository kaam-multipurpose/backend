<?php

use App\Models\Category;
use App\Models\VariantType;
use Symfony\Component\HttpFoundation\Response;

describe('Add Category', function (): void {
    beforeEach(function (): void {
        $this->categoryName = 'Test Category';
        $this->variantTypes = VariantType::factory()->count(3)->create();
        $this->variantTypeIds = $this->variantTypes->pluck('id')->toArray();

        $this->validData = [
            'name' => $this->categoryName,
            'variant_type_ids' => $this->variantTypeIds,
        ];
    });

    describe('Authorization', function (): void {
        it('allows super admin to create a category', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->postJson('/api/categories', $this->validData);

            $response->assertStatus(Response::HTTP_CREATED);
            $response->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'slug',
                ],
                'message',
            ]);

            // Verify the category was created
            $category = Category::where('name', $this->categoryName)->first();
            expect($category)->not->toBeNull()
                ->and($category->variantTypes()->count())->toBe(count($this->variantTypeIds))
                ->and($category->parent_id)->toBeNull();
        });

        it('allows admin to create a category', function (): void {
            $this->actingAs($this->adminUser, 'sanctum');

            $response = $this->postJson('/api/categories', $this->validData);

            $response->assertStatus(Response::HTTP_CREATED);
        });

        it('prevents unauthorized users from creating categories', function (): void {
            $this->actingAs($this->salesRepUser, 'sanctum');

            $response = $this->postJson('/api/categories', $this->validData);

            $response->assertStatus(Response::HTTP_FORBIDDEN);
        });

        it('requires authentication', function (): void {
            $response = $this->postJson('/api/categories', $this->validData);

            $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        });
    });

    describe('Validation', function (): void {
        beforeEach(function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');
        });

        it('requires a name', function (): void {
            $data = $this->validData;
            unset($data['name']);

            $response = $this->postJson('/api/categories', $data);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors(['name']);
        });

        it('requires name to be at least 3 characters', function (): void {
            $data = array_merge($this->validData, ['name' => 'ab']);

            $response = $this->postJson('/api/categories', $data);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors(['name']);
        });

        it('requires unique category name', function (): void {
            // Create a category first
            Category::create(['name' => $this->categoryName]);

            $response = $this->postJson('/api/categories', $this->validData);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors(['name']);
        });

        it('requires variant type ids', function (): void {
            $data = $this->validData;
            unset($data['variant_type_ids']);

            $response = $this->postJson('/api/categories', $data);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors(['variant_type_ids']);
        });

        it('requires at least one variant type', function (): void {
            $data = array_merge($this->validData, ['variant_type_ids' => []]);

            $response = $this->postJson('/api/categories', $data);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors(['variant_type_ids']);
        });

        it('validates variant type ids exist', function (): void {
            $data = array_merge($this->validData, [
                'variant_type_ids' => [999999], // Non-existent ID
            ]);

            $response = $this->postJson('/api/categories', $data);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors(['variant_type_ids.0']);
        });
    });

    describe('Success Cases', function (): void {
        beforeEach(function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');
        });

        it('creates a category with variant types', function (): void {
            $response = $this->postJson('/api/categories', $this->validData);

            $response->assertStatus(Response::HTTP_CREATED);

            $category = Category::where('name', $this->categoryName)->first();
            expect($category)->not->toBeNull();

            // Check variant types are attached
            $attachedVariantTypeIds = $category->variantTypes()->pluck('id')->toArray();
            expect($attachedVariantTypeIds)->toEqual($this->variantTypeIds);
        });

        it('generates correct slug for category', function (): void {
            $data = array_merge($this->validData, ['name' => 'Test Category Name']);

            $response = $this->postJson('/api/categories', $data);

            $response->assertStatus(Response::HTTP_CREATED);
            $category = Category::where('name', 'Test Category Name')->first();
            expect($category->slug)->toBe('test-category-name');
        });
    });
});
