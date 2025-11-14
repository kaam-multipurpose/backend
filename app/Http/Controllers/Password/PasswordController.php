<?php

declare(strict_types=1);

namespace App\Http\Controllers\Password;

use App\Dto\ChangePasswordDto;
use App\Dto\ResetPasswordDto;
use App\Exceptions\PasswordServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use App\Services\Contracts\PasswordServiceContract;
use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

final class PasswordController extends Controller
{
    use HasAuthenticatedUser;
    use HasLogger;

    public function __construct(private PasswordServiceContract $passwordService) {}

    /**
     * @throws PasswordServiceException
     */
    public function forgetPassword(Request $request): JsonResponse
    {

        $email = $request->validate(['email' => 'required|email|exists:users,email'])['email'];

        if ($this->passwordService->forgetPassword(email: $email)) {
            self::logInfo('Reset password otp sent to '.$email, [
                'email' => $email,
            ]);

            return ApiResponse::success(message: 'An Otp has been sent to your email.');
        }

        self::logInfo('Reset password otp not sent to '.$email, [
            'email' => $email,
        ]);

        return ApiResponse::error(message: 'An error occurred while sending your Otp.');
    }

    /**
     * @throws PasswordServiceException
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();

        $this->passwordService->resetPassword(ResetPasswordDto::fromValidated($data));

        self::logInfo('Password reset successfully for '.$data['email'], [
            'email' => $data['email'],
        ]);

        return ApiResponse::success(message: 'Password reset successfully.');
    }

    /**
     * @throws PasswordServiceException
     */
    public function changePassword(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ]);

        $this->passwordService->changePassword(
            ChangePasswordDto::fromValidated($data),
            $user
        );

        self::logInfo('Password changed successfully for');

        return ApiResponse::success(message: 'Password changed successfully.')
            ->withCookie(Cookie::forget('refresh_token'));
    }
}
