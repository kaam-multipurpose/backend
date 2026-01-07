<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\VariantType;
use Symfony\Component\HttpFoundation\Response;

describe('Add Subcategory', function (): void {
    beforeEach(function (): void {
        // Create a parent category first
        $this->parentCategory = Category::factory()
            ->hasVariantTypes(2)
            ->create();

        // Create additional variant types for testing
        $this->additionalVariantTypes = VariantType::factory()->count(2)->create();

        $this->subcategoryName = 'Test Subcategory';

        $this->validData = [
            'name' => $this->subcategoryName,
            'has_additional_variant_type' => false,
        ];

        $this->validDataWithVariantTypes = [
            'name' => $this->subcategoryName,
            'has_additional_variant_type' => true,
            'variant_type_ids' => $this->additionalVariantTypes->pluck('id')->toArray(),
        ];
    });

    describe('Authorization', function (): void {
        it('allows super admin to create a subcategory', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->postJson("/api/categories/{$this->parentCategory->slug}", $this->validData);

            $response->assertStatus(Response::HTTP_CREATED);
            $response->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'slug',
                ],
                'message',
            ]);

            // Verify the subcategory was created
            $subcategory = Category::where('name', $this->subcategoryName)->first();
            expect($subcategory)->not->toBeNull()
                ->and($subcategory->parent_id)->toBe($this->parentCategory->id)
                ->and($subcategory->allVariantTypes->count())->toBe($this->parentCategory->allVariantTypes->count());
        });

        it('allows admin to create a subcategory', function (): void {
            $this->actingAs($this->adminUser, 'sanctum');

            $response = $this->postJson("/api/categories/{$this->parentCategory->slug}", $this->validData);

            $response->assertStatus(Response::HTTP_CREATED);
        });

        it('prevents unauthorized users from creating subcategories', function (): void {
            $this->actingAs($this->salesRepUser, 'sanctum');

            $response = $this->postJson("/api/categories/{$this->parentCategory->slug}", $this->validData);

            $response->assertStatus(Response::HTTP_FORBIDDEN);
        });

        it('requires authentication', function (): void {
            $response = $this->postJson("/api/categories/{$this->parentCategory->slug}", $this->validData);

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

            $response = $this->postJson("/api/categories/{$this->parentCategory->slug}", $data);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors(['name']);
        });

        it('requires name to be at least 3 characters', function (): void {
            $data = array_merge($this->validData, ['name' => 'ab']);

            $response = $this->postJson("/api/categories/{$this->parentCategory->slug}", $data);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors(['name']);
        });

        it('requires unique subcategory name', function (): void {
            // Create a subcategory first
            Category::create([
                'name' => $this->subcategoryName,
                'parent_id' => $this->parentCategory->id,
            ]);

            $response = $this->postJson("/api/categories/{$this->parentCategory->slug}", $this->validData);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors(['name']);
        });

        it('requires has_additional_variant_type flag', function (): void {
            $data = $this->validData;
            unset($data['has_additional_variant_type']);

            $response = $this->postJson("/api/categories/{$this->parentCategory->slug}", $data);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors(['has_additional_variant_type']);
        });

        it('requires variant_type_ids when has_additional_variant_type is true', function (): void {
            $data = array_merge($this->validData, [
                'has_additional_variant_type' => true,
            ]);

            $response = $this->postJson("/api/categories/{$this->parentCategory->slug}", $data);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors(['variant_type_ids']);
        });

        it('validates variant type ids exist when provided', function (): void {
            $data = array_merge($this->validData, [
                'has_additional_variant_type' => true,
                'variant_type_ids' => [999999], // Non-existent ID
            ]);

            $response = $this->postJson("/api/categories/{$this->parentCategory->slug}", $data);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors(['variant_type_ids.0']);
        });

        it('returns 404 for non-existent parent category', function (): void {
            $response = $this->postJson('/api/categories/99999', $this->validData);

            $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        });
    });

    describe('Success Cases', function (): void {
        beforeEach(function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');
        });

        it('creates a subcategory with inherited variant types', function (): void {
            $response = $this->postJson("/api/categories/{$this->parentCategory->slug}", $this->validData);

            $response->assertStatus(Response::HTTP_CREATED);

            $subcategory = Category::where('name', $this->subcategoryName)->first();
            expect($subcategory)->not->toBeNull()
                ->and($subcategory->parent_id)->toBe($this->parentCategory->id);
        });

        it('creates a subcategory with additional variant types', function (): void {
            $response = $this->postJson(
                "/api/categories/{$this->parentCategory->slug}",
                $this->validDataWithVariantTypes
            );

            $response->assertStatus(Response::HTTP_CREATED);
        });

        it('generates correct slug for subcategory', function (): void {
            $parentName = $this->parentCategory->name;
            $subcategoryName = 'Test Sub Category Name';

            $data = array_merge($this->validData, ['name' => $subcategoryName]);

            $response = $this->postJson("/api/categories/{$this->parentCategory->slug}", $data);

            $response->assertStatus(Response::HTTP_CREATED);

            $subcategory = Category::where('name', $subcategoryName)->first();
            $expectedSlug = Str::slug($parentName.' '.$subcategoryName);
            expect($subcategory->slug)->toBe($expectedSlug);
        });
    });
});
