<?php

declare(strict_types=1);

use App\Models\VariantType;
use App\Models\VariantTypeValue;
use Symfony\Component\HttpFoundation\Response;

describe('Delete Variant Type', function (): void {
    beforeEach(function (): void {
        $this->variantType = VariantType::factory()
            ->hasVariantTypeValues(3)
            ->create();

        $this->variantTypeValue = $this->variantType->variantTypeValues->first();
    });

    describe('Delete Variant Type', function (): void {
        it('allows super admin to delete a variant type', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->deleteJson("/api/variant-types/{$this->variantType->slug}");

            $response->assertStatus(Response::HTTP_NO_CONTENT);

            // Verify variant type is soft deleted
            expect(VariantType::find($this->variantType->id))->toBeNull()
                ->and(VariantType::withTrashed()->find($this->variantType->id))->not->toBeNull();

            // Verify associated values are soft deleted
            $variantValueIds = $this->variantType->variantTypeValues->pluck('id');
            expect(VariantTypeValue::whereIn('id', $variantValueIds)->count())->toBe(0)
                ->and(VariantTypeValue::withTrashed()->whereIn('id', $variantValueIds)->count())->toBe(3);
        });

        it('allows admin to delete a variant type', function (): void {
            $this->actingAs($this->adminUser, 'sanctum');

            $response = $this->deleteJson("/api/variant-types/{$this->variantType->slug}");

            $response->assertStatus(Response::HTTP_NO_CONTENT);

            expect(VariantType::find($this->variantType->id))->toBeNull();
        });

        it('prevents unauthorized users from deleting variant types', function (): void {
            $this->actingAs($this->salesRepUser, 'sanctum');

            $response = $this->deleteJson("/api/variant-types/{$this->variantType->slug}");

            $response->assertStatus(Response::HTTP_FORBIDDEN);

            // Verify variant type still exists
            expect(VariantType::find($this->variantType->id))->not->toBeNull();
        });

        it('returns 404 for non-existent variant type', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->deleteJson('/api/variant-types/non-existent-slug');

            $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        });
    });

    describe('Delete Variant Type Value', function (): void {
        it('allows super admin to delete a variant value', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->deleteJson(
                "/api/variant-types/{$this->variantType->slug}/{$this->variantTypeValue->slug}"
            );

            $response->assertStatus(Response::HTTP_NO_CONTENT);

            expect(VariantTypeValue::find($this->variantTypeValue->id))->toBeNull()
                ->and($this->variantType->variantTypeValues()->count())->toBe(2);

        });

        it('allows admin to delete a variant value', function (): void {
            $this->actingAs($this->adminUser, 'sanctum');

            $response = $this->deleteJson(
                "/api/variant-types/{$this->variantType->slug}/{$this->variantTypeValue->slug}"
            );

            $response->assertStatus(Response::HTTP_NO_CONTENT);
            expect(VariantTypeValue::find($this->variantTypeValue->id))->toBeNull();
        });

        it('prevents unauthorized users from deleting variant values', function (): void {
            $this->actingAs($this->salesRepUser, 'sanctum');

            $response = $this->deleteJson(
                "/api/variant-types/{$this->variantType->slug}/{$this->variantTypeValue->slug}"
            );

            $response->assertStatus(Response::HTTP_FORBIDDEN);
            expect(VariantTypeValue::find($this->variantTypeValue->id))->not->toBeNull();
        });

        it('returns 404 for non-existent variant value', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->deleteJson(
                "/api/variant-types/{$this->variantType->slug}/non-existent-value"
            );

            $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        });

        it('returns 404 when value does not belong to variant type', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            // Create another variant type and value
            $otherVariantType = VariantType::factory()
                ->hasVariantTypeValues()
                ->create();
            $otherValue = $otherVariantType->variantTypeValues->first();

            // Try to delete value using wrong variant type
            $response = $this->deleteJson(
                "/api/variant-types/{$this->variantType->slug}/{$otherValue->slug}"
            );

            $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
            expect(VariantTypeValue::find($otherValue->id))->not->toBeNull();
        });

    });
});
