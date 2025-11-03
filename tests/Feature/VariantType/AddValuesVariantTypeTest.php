<?php

use App\Models\VariantType;
use Symfony\Component\HttpFoundation\Response;

describe('Add Values To Variant Type', function (): void {
    beforeEach(function (): void {
        $this->variantType = VariantType::factory()->hasVariantTypeValues(2)->create();
        $this->newValues = ['New Value 1', 'New Value 2'];
    });

    it('allows super admin to add values to variant type', function (): void {
        $this->actingAs($this->superAdminUser, 'sanctum');

        $response = $this->postJson("/api/variant-types/{$this->variantType->slug}", [
            'values' => $this->newValues,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $response->assertJsonStructure([
            'data' => [
                '*' => ['name'],
            ],
            'message',
        ]);

    });

    it('allows admin to add values to variant type', function (): void {
        $this->actingAs($this->adminUser, 'sanctum');

        $response = $this->postJson("/api/variant-types/{$this->variantType->slug}", [
            'values' => $this->newValues,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    });

    it('prevents unauthorized users from adding values', function (): void {
        $this->actingAs($this->salesRepUser, 'sanctum');

        $response = $this->postJson("/api/variant-types/{$this->variantType->slug}", [
            'values' => $this->newValues,
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    });

    it('validates values array is required', function (): void {
        $this->actingAs($this->superAdminUser, 'sanctum');

        $response = $this->postJson("/api/variant-types/{$this->variantType->slug}", []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['values']);
    });

    it('validates values must be strings', function (): void {
        $this->actingAs($this->superAdminUser, 'sanctum');

        $response = $this->postJson("/api/variant-types/{$this->variantType->slug}", [
            'values' => ['Valid String', 123, true],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['values.1', 'values.2']);
    });

    it('validates minimum length of values', function (): void {
        $this->actingAs($this->superAdminUser, 'sanctum');

        $response = $this->postJson("/api/variant-types/{$this->variantType->slug}", [
            'values' => ['', 'A'],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    });

    it('returns 404 for non-existent variant type', function (): void {
        $this->actingAs($this->superAdminUser, 'sanctum');

        $response = $this->postJson('/api/variant-types/non-existent-slug', [
            'values' => $this->newValues,
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    });
});
