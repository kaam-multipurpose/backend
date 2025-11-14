<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Response;

describe('Permission Service Test', function (): void {
    describe('get all Permissions', function (): void {
        it('returns all permissions when logged in as super admin or admin', function (): void {
            $users = [
                $this->superAdminUser,
                $this->adminUser,
            ];
            $selected = $users[random_int(0, 1)];
            $this->actingAs($selected, 'sanctum');

            $response = $this->get('/api/permissions');

            expect($response->getStatusCode())->toBe(Response::HTTP_OK);
        });

        it('throws an error when others aside super admin or admin tries to get all permission', function (): void {
            $this->actingAs($this->salesRepUser, 'sanctum');

            $response = $this->get('/api/permissions');

            expect($response->getStatusCode())->toBe(Response::HTTP_FORBIDDEN);
        });
    });

});
