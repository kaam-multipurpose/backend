<?php

declare(strict_types=1);

use App\Models\VariantType;

describe('Getting Variant Types', function (): void {
    beforeEach(function (): void {
        VariantType::factory()->count(3)->hasVariantTypeValues(4)->create();
    });

    it('allows super admin to get variant types', function (): void {
        $this->actingAs($this->superAdminUser, 'sanctum');

        $response = $this->getJson('/api/variant-types');

        $response->assertOk();
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'data' => [
                    '*' => [
                        'name',
                        'slug',
                        'values',
                    ],
                ],
                'meta',
            ],
        ]);
    });

    it('allows admin to get variant types', function (): void {
        $this->actingAs($this->adminUser, 'sanctum');

        $response = $this->getJson('/api/variant-types');

        $response->assertOk();
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'data' => [
                    '*' => [
                        'name',
                        'slug',
                        'values',
                    ],
                ],
            ],
        ]);
    });

    it('allows pagination of variant types', function (): void {
        $this->actingAs($this->superAdminUser, 'sanctum');

        // Create more variant types to test pagination with associated values
        VariantType::factory()->count(10)->hasVariantTypeValues(4)->create();

        $response = $this->getJson('/api/variant-types?page=2&row=5');

        $response->assertOk();
        $response->assertJsonPath('data.meta.current_page', 2);
        $response->assertJsonPath('data.meta.per_page', 5);
    });

    it('denies unauthorized users from getting variant types', function (): void {
        $this->actingAs($this->salesRepUser, 'sanctum');

        $response = $this->getJson('/api/variant-types');

        $response->assertForbidden();
    });
});
