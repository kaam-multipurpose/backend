<?php

use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

describe('Change Password', function (): void {
    beforeEach(function (): void {
        $this->oldPassword = TEST_USER_PASSWORD;
        $this->newPassword = 'newPassword123';

        $this->testUser = $this->salesRepUser;
    });

    it('allows authenticated user to change their password', function (): void {
        $this->actingAs($this->testUser, 'sanctum');

        $response = $this->patchJson('/api/password/change/'.$this->testUser->id, [
            'current_password' => $this->oldPassword,
            'new_password' => $this->newPassword,
            'new_password_confirmation' => $this->newPassword,
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['data', 'message']);

        // Verify the password was actually changed
        expect(Hash::check($this->newPassword, $this->testUser->fresh()->password))->toBeTrue()
            ->and(Hash::check($this->oldPassword, $this->testUser->fresh()->password))->toBeFalse();
    });

    it('requires authentication to change password', function (): void {
        $response = $this->patchJson('/api/password/change/'.$this->testUser->id, [
            'current_password' => $this->oldPassword,
            'new_password' => $this->newPassword,
            'new_password_confirmation' => $this->newPassword,
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    });

    it('validates current password is correct', function (): void {
        $this->actingAs($this->testUser, 'sanctum');

        $response = $this->patchJson('/api/password/change/'.$this->testUser->id, [
            'current_password' => 'wrongPassword',
            'new_password' => $this->newPassword,
            'new_password_confirmation' => $this->newPassword,
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        // Verify password wasn't changed
        expect(Hash::check($this->oldPassword, $this->testUser->fresh()->password))->toBeTrue();
    });

    it('validates new password meets minimum requirements', function (): void {
        $this->actingAs($this->testUser, 'sanctum');

        $response = $this->patchJson('/api/password/change/'.$this->testUser->id, [
            'current_password' => $this->oldPassword,
            'new_password' => 'short',
            'new_password_confirmation' => 'short',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['new_password']);

        // Verify password wasn't changed
        expect(Hash::check($this->oldPassword, $this->testUser->fresh()->password))->toBeTrue();
    });

    it('validates new password confirmation matches', function (): void {
        $this->actingAs($this->testUser, 'sanctum');

        $response = $this->patchJson('/api/password/change/'.$this->testUser->id, [
            'current_password' => $this->oldPassword,
            'new_password' => $this->newPassword,
            'new_password_confirmation' => 'differentPassword123',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['new_password']);

        // Verify password wasn't changed
        expect(Hash::check($this->oldPassword, $this->testUser->fresh()->password))->toBeTrue();
    });

    it('requires current password field', function (): void {
        $this->actingAs($this->testUser, 'sanctum');

        $response = $this->patchJson('/api/password/change/'.$this->testUser->id, [
            'new_password' => $this->newPassword,
            'new_password_confirmation' => $this->newPassword,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['current_password']);
    });

    it('requires new password field', function (): void {
        $this->actingAs($this->testUser, 'sanctum');

        $response = $this->patchJson('/api/password/change/'.$this->testUser->id, [
            'current_password' => $this->oldPassword,
            'new_password_confirmation' => $this->newPassword,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['new_password']);
    });
});
