<?php

use Symfony\Component\HttpFoundation\Response;

describe('Permission Service Test', function () {
    describe('get all Permissions', function () {
        it('returns all permissions when logged in as super admin or admin', function () {
            $users = [
                $this->superAdminUser,
                $this->adminUser,
            ];
            $selected = $users[rand(0, 1)];
            $this->actingAs($selected, 'sanctum');

            $response = $this->get('/api/permissions');

            expect($response->getStatusCode())->toBe(Response::HTTP_OK);
        });

        it('throws an error when others aside super admin or admin tries to get all permission', function () {
            $this->actingAs($this->salesRepUser, 'sanctum');

            $response = $this->get('/api/permissions');

            expect($response->getStatusCode())->toBe(Response::HTTP_FORBIDDEN);
        });
    });

});
