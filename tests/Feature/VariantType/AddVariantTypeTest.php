<?php

declare(strict_types=1);

use App\Models\VariantType;
use Symfony\Component\HttpFoundation\Response;

describe('Adding Variant Type', function (): void {
    beforeEach(function (): void {
        $this->variantTypeName = 'Test Variant Type';
        $this->variantTypeValues = ['Value 1', 'Value 2', 'Value 3'];
    });

    it('allows super admin to create a variant type with values', function (): void {
        $this->actingAs($this->superAdminUser, 'sanctum');

        $response = $this->postJson('/api/variant-types', [
            'name' => $this->variantTypeName,
            'values' => $this->variantTypeValues,
        ]);

        $response->assertCreated();
        $response->assertJsonFragment(['name' => ucwords($this->variantTypeName)]);

        $variantType = VariantType::query()->where('name', ucwords($this->variantTypeName))->first();
        expect($variantType)->not->toBeNull()
            ->and($variantType->variantTypeValues->pluck('name')->count())->toBe(count($this->variantTypeValues));
    });

    it('allows admin to create a variant type with values', function (): void {
        $this->actingAs($this->adminUser, 'sanctum');

        $response = $this->postJson('/api/variant-types', [
            'name' => $this->variantTypeName,
            'values' => $this->variantTypeValues,
        ]);

        $response->assertCreated();
        $response->assertJsonFragment(['name' => ucwords($this->variantTypeName)]);

        $variantType = VariantType::query()->where('name', ucwords($this->variantTypeName))->first();
        expect($variantType)->not->toBeNull()
            ->and($variantType->variantTypeValues->pluck('name')->count())->toBe(count($this->variantTypeValues));
    });

    it('denies unauthorized users from creating variant types', function (): void {
        $this->actingAs($this->salesRepUser, 'sanctum');

        $response = $this->postJson('/api/variant-types', [
            'name' => $this->variantTypeName,
            'values' => $this->variantTypeValues,
        ]);

        $response->assertForbidden();
    });

    it('validates that variant type name is required', function (): void {
        $this->actingAs($this->superAdminUser, 'sanctum');

        $response = $this->postJson('/api/variant-types', [
            'values' => $this->variantTypeValues,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['name']);
    });

    it('validates that variant type values are required', function (): void {
        $this->actingAs($this->superAdminUser, 'sanctum');

        $response = $this->postJson('/api/variant-types', [
            'name' => $this->variantTypeName,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['values']);
    });

    it('validates that variant type name has minimum length', function (): void {
        $this->actingAs($this->superAdminUser, 'sanctum');

        $response = $this->postJson('/api/variant-types', [
            'name' => 'ab',
            'values' => $this->variantTypeValues,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['name']);
    });

    it('validates that variant type has at least one values', function (): void {
        $this->actingAs($this->superAdminUser, 'sanctum');

        $response = $this->postJson('/api/variant-types', [
            'name' => $this->variantTypeName,
            'values' => [''],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['values.0']);
    });
});
