<?php

declare(strict_types=1);

use App\Models\Unit;
use Symfony\Component\HttpFoundation\Response;

describe('Delete Unit', function (): void {
    beforeEach(function (): void {
        $this->unit = Unit::factory()->create();
    });

    describe('Authorization', function (): void {
        it('allows super admin to delete a unit', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->deleteJson("/api/units/{$this->unit->id}");

            $response->assertStatus(Response::HTTP_NO_CONTENT);

            // Verify the unit was deleted
            $this->assertSoftDeleted('units', ['id' => $this->unit->id]);
        });

        it('prevents unauthorized users from deleting units', function (): void {
            $this->actingAs($this->salesRepUser, 'sanctum');

            $response = $this->deleteJson("/api/units/{$this->unit->id}");

            $response->assertStatus(Response::HTTP_FORBIDDEN);
        });

        it('requires authentication', function (): void {
            $response = $this->deleteJson("/api/units/{$this->unit->id}");

            $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        });
    });
});
