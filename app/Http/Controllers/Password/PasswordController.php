<?php

namespace App\Http\Controllers\Password;

use App\Dto\ResetPasswordDto;
use App\Exceptions\PasswordServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\Contracts\PasswordServiceContract;
use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    use HasAuthenticatedUser, HasLogger;

    public function __construct(protected PasswordServiceContract $passwordService) {}

    /**
     * @throws PasswordServiceException
     */
    public function forgetPassword(Request $request): JsonResponse
    {

        $email = $request->validate(['email' => 'required|email|exists:users,email'])['email'];

        if ($this->passwordService->forgetPassword(email: $email)) {
            self::logInfo("Reset password otp sent to $email", [
                'email' => $email,
            ]);

            return ApiResponse::success(message: 'An Otp has been sent to your email.');
        }

        self::logInfo("Reset password otp not sent to $email", [
            'email' => $email,
        ]);

        return ApiResponse::error(message: 'An error occurred while sending your Otp.');
    }

    /**
     * @throws PasswordServiceException
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $data = $request->validated();

        $this->passwordService->resetPassword(ResetPasswordDto::fromValidated($data));

        self::logInfo('Password reset successfully for ' . $data['email'], [
            'email' => $data['email'],
        ]);

        return ApiResponse::success(message: 'Password reset successfully.');
    }

    /**
     * @throws PasswordServiceException
     */
    public function changePassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        self::logInfo('Password Data ', [
            'data' => $data,
        ]);
        $user = $this->getLoggedInUser();
        if (!$user) {
            return ApiResponse::error(message: 'User is not authenticated.', status: 401);
        }

        $this->passwordService->changePassword($user["id"], $data['current_password'], $data['new_password']);

        self::logInfo('Password changed successfully for ' . $user->email, [
            'email' => $user->email,
        ]);

        return ApiResponse::success(message: 'Password changed successfully.');
    }
}
