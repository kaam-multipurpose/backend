<?php

declare(strict_types=1);

use App\Mail\ApplicationMail;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

describe('Forgot Password', function (): void {

    beforeEach(function (): void {
        Mail::fake();
    });
    it('sends an OTP to admin user', function (): void {
        $response = $this->postJson('/api/password/forgot', [
            'email' => $this->adminUser->email,
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'success',
            'message',
            'data',
        ]);
        $response->assertJson([
            'success' => true,
            'message' => 'An Otp has been sent to your email.',
            'data' => null,
        ]);

        Mail::assertQueued(
            ApplicationMail::class,
            fn ($mail) => $mail->hasTo($this->adminUser->email)
        );
    });

    it('sends an OTP to sales rep user', function (): void {
        $response = $this->postJson('/api/password/forgot', [
            'email' => $this->salesRepUser->email,
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'success',
            'message',
            'data',
        ]);
        $response->assertJson([
            'success' => true,
            'message' => 'An Otp has been sent to your email.',
            'data' => null,
        ]);

        Mail::assertQueued(
            ApplicationMail::class,
            fn ($mail) => $mail->hasTo($this->salesRepUser->email)
        );
    });

    it('sends an OTP to super admin user', function (): void {
        $response = $this->postJson('/api/password/forgot', [
            'email' => $this->superAdminUser->email,
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'success',
            'message',
            'data',
        ]);
        $response->assertJson([
            'success' => true,
            'message' => 'An Otp has been sent to your email.',
            'data' => null,
        ]);

        Mail::assertQueued(
            ApplicationMail::class,
            fn ($mail) => $mail->hasTo($this->superAdminUser->email)
        );
    });

    it('returns error for non-existent email', function (): void {
        $response = $this->postJson('/api/password/forgot', [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'success',
            'message',
            'errors' => ['email'],
        ]);

        Mail::assertNothingQueued();
    });

    it('validates email format', function (): void {
        $response = $this->postJson('/api/password/forgot', [
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'success',
            'message',
            'errors' => ['email'],
        ]);

        Mail::assertNothingQueued();
    });

    it('requires email field', function (): void {
        $response = $this->postJson('/api/password/forgot', []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'success',
            'message',
            'errors' => ['email'],
        ]);

        Mail::assertNothingQueued();
    });
});
