<?php

use App\Models\PasswordResetToken;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

describe('Reset Password', function (): void {
    beforeEach(function (): void {
        $this->resetToken = Str::random(6);

        PasswordResetToken::factory()
            ->forUser($this->adminUser)
            ->create(['token' => $this->resetToken]);

        PasswordResetToken::factory()
            ->forUser($this->salesRepUser)
            ->create(['token' => $this->resetToken]);
    });

    it('successfully resets password with valid token', function (): void {
        $response = $this->postJson("/api/password/reset/{$this->adminUser->email}/{$this->resetToken}", [
            'password' => 'newPassword123',
            'password_confirmation' => 'newPassword123',
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'success',
            'message',
            'data',
        ]);
        $response->assertJson([
            'success' => true,
            'message' => 'Password reset successfully.',
            'data' => null,
        ]);

        expect(PasswordResetToken::where('email', $this->adminUser->email)->exists())
            ->toBeFalse();
    });

    it('successfully resets password for sales rep user', function (): void {

        $response = $this->postJson("/api/password/reset/{$this->salesRepUser->email}/{$this->resetToken}", [
            'password' => 'newPassword123',
            'password_confirmation' => 'newPassword123',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Password reset successfully.',
            'data' => null,
        ]);
    });

    it('fails with invalid token', function (): void {
        $response = $this->postJson("/api/password/reset/{$this->adminUser->email}/invalid-token", [
            'password' => 'newPassword123',
            'password_confirmation' => 'newPassword123',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'success',
            'message',
        ]);
    });

    it('fails with expired token', function (): void {
        // Update token to be expired
        PasswordResetToken::where('email', $this->adminUser->email)
            ->update(['expires_at' => now()->subMinutes(1)]);

        $response = $this->postJson("/api/password/reset/{$this->adminUser->email}/{$this->resetToken}", [
            'password' => 'newPassword123',
            'password_confirmation' => 'newPassword123',
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonStructure([
            'success',
            'message',
        ]);
    });

    it('fails with non-existent email', function (): void {
        $response = $this->postJson("/api/password/reset/nonexistent@example.com/{$this->resetToken}", [
            'password' => 'newPassword123',
            'password_confirmation' => 'newPassword123',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'success',
            'message',
            'errors' => ['email'],
        ]);
    });

    it('validates password confirmation match', function (): void {
        $response = $this->postJson("/api/password/reset/{$this->adminUser->email}/{$this->resetToken}", [
            'password' => 'newPassword123',
            'password_confirmation' => 'differentPassword123',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'success',
            'message',
            'errors' => ['password'],
        ]);
    });
});
