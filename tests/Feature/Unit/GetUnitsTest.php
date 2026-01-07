<?php

declare(strict_types=1);

use App\Models\Unit;
use Symfony\Component\HttpFoundation\Response;

describe('Get Units', function (): void {
    beforeEach(function (): void {
        // Create some units
        Unit::factory()->count(15)->create();
    });

    describe('Authorization', function (): void {
        it('allows super admin to list units', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->getJson('/api/units');

            $response->assertStatus(Response::HTTP_OK);
            $response->assertJsonStructure([
                'message',
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'symbol',
                        ]
                    ],
                    'links',
                    'meta',
                ],
            ]);
        });

        it('prevents unauthorized users from listing units', function (): void {
            $this->actingAs($this->salesRepUser, 'sanctum'); // Assuming sales rep doesn't have VIEW_UNIT

            $response = $this->getJson('/api/units');

            $response->assertStatus(Response::HTTP_FORBIDDEN);
        });

        it('requires authentication', function (): void {
            $response = $this->getJson('/api/units');

            $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        });
    });

    describe('Pagination', function (): void {
        beforeEach(function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');
        });

        it('paginates results', function (): void {
            $response = $this->getJson('/api/units?per_page=5');

            $response->assertStatus(Response::HTTP_OK);
            $response->assertJsonCount(5, 'data.data');
            $response->assertJsonFragment(['per_page' => 5]);
        });

        it('returns correct page', function (): void {
            $response = $this->getJson('/api/units?per_page=5&page=2');

            $response->assertStatus(Response::HTTP_OK);
            $response->assertJsonFragment(['current_page' => 2]);
        });
    });
});
