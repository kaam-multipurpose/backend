<?php

test('user can logout successfully', function (): void {
    $this->actingAs($this->salesRepUser, 'sanctum');

    $logoutResponse = $this->deleteJson('/api/logout');

    $logoutResponse->assertStatus(200);
    $logoutResponse->assertJson([
        'success' => true,
        'message' => 'Logged out successfully.',
    ]);

    $this->assertDatabaseMissing('personal_access_tokens', [
        'tokenable_id' => $this->salesRepUser->id,
    ]);

    $this->assertDatabaseMissing('refresh_tokens', [
        'user_id' => $this->salesRepUser->id,
    ]);
});
