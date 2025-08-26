<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class LogoutTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_logout_successfully(): void
    {

        $this->actingAs($this->salesRepUser, 'sanctum');

        // Call logout with token and cookie
        $logoutResponse = $this->deleteJson('/api/logout');

        $logoutResponse->assertStatus(200);
        $logoutResponse->assertJson([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);

        // Assert tokens are deleted
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $this->salesRepUser->id,
        ]);

        $this->assertDatabaseMissing('refresh_tokens', [
            'user_id' => $this->salesRepUser->id,
        ]);

    }
}
