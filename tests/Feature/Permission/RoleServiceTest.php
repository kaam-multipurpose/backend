<?php

declare(strict_types=1);

use App\Enum\PermissionsEnum;
use App\Models\Role;

describe('Role Service', function (): void {

    describe('Adding Role', function (): void {

        beforeEach(function (): void {
            $this->permissions = collect(PermissionsEnum::values())->take(6);
            $this->roleName = 'test-role';
        });

        it('allows super admin to create a role with permissions', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->postJson('/api/roles', [
                'role' => $this->roleName,
                'permissions' => $this->permissions,
            ]);

            $response->assertCreated();
            $response->assertJsonFragment(['name' => $this->roleName]);

            $role = Role::where('name', $this->roleName)->first();
            expect($role)->not->toBeNull()
                ->and($role->permissions->pluck('name')->sort()->values()->toArray())
                ->toEqual($this->permissions->sort()->values()->toArray());
        });

        it('denies unauthorized users from creating roles', function (): void {
            $user = collect([$this->adminUser, $this->salesRepUser])->random();
            $this->actingAs($user, 'sanctum');

            $response = $this->postJson('/api/roles', [
                'role' => $this->roleName,
                'permissions' => $this->permissions,
            ]);

            $response->assertForbidden();
        });
    });

    describe('Editing Role', function (): void {

        beforeEach(function (): void {
            $this->role = Role::where('name', 'admin')->firstOrFail();
            $this->newPermissions = [
                ...$this->role->getPermissionNames()->toArray(),
                PermissionsEnum::ASSIGN_PERMISSIONS->value,
            ];
        });

        it('allows super admin to update role permissions', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->patchJson("/api/roles/{$this->role->slug}", [
                'permissions' => $this->newPermissions,
            ]);

            $response->assertOk();
            $this->role->refresh();
            expect($this->role->getPermissionNames()->sort()->values()->toArray())
                ->toEqual(collect($this->newPermissions)->sort()->values()->toArray());
        });

        it('returns 404 when trying to edit a non-existent role', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->patchJson('/api/roles/non-existent-role', [
                'permissions' => $this->newPermissions,
            ]);

            $response->assertNotFound();
        });
    });

    describe('Deleting Role', function (): void {

        beforeEach(function (): void {
            $this->predefinedRole = Role::where('name', 'super admin')->firstOrFail();
            $this->deletableRole = Role::create([
                'name' => 'temporary-role',
            ]);
        });

        it('allows super admin to delete a role', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->deleteJson("/api/roles/{$this->deletableRole->slug}");

            $response->assertOk();
            expect(Role::find($this->deletableRole->id))->toBeNull();
        });

        it('prevents deletion of predefined roles', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->deleteJson("/api/roles/{$this->predefinedRole->slug}");

            $response->assertForbidden();
            expect(Role::find($this->predefinedRole->id))->not->toBeNull();
        });
    });

    describe('Fetching Roles', function (): void {

        it('returns the full list of roles', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->getJson('/api/roles');

            $response->assertOk();
        });

        it('returns a single role by slug', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $role = Role::where('name', 'admin')->firstOrFail();

            $response = $this->getJson("/api/roles/{$role->slug}");

            $response->assertOk();
            $response->assertJsonFragment(['name' => $role->name]);
        });

        it('returns 404 for unknown role slug', function (): void {
            $this->actingAs($this->superAdminUser, 'sanctum');

            $response = $this->getJson('/api/roles/unknown-slug');

            $response->assertNotFound();
        });
    });
});
