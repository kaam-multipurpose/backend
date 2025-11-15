<?php

declare(strict_types=1);

namespace App\Services\Password;

use App\Dtos\ChangePasswordDto;
use App\Dtos\Mail\ForgetPasswordMailDto;
use App\Dtos\ResetPasswordDto;
use App\Exceptions\PasswordServiceException;
use App\Mail\ApplicationMail;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Services\Contracts\PasswordServiceContract;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class PasswordService implements PasswordServiceContract
{
    use HasAuthenticatedUser;
    use HasLogger;

    public function forgetPassword(string $email): bool
    {
        try {
            self::logInfo('Attempting to forget the password for '.$email, [
                'email' => $email,
            ]);

            $token = Str::random(6);
            PasswordResetToken::query()->updateOrCreate([
                'email' => $email,
            ], [
                'token' => $token,
                'expires_at' => now()->addHours(3),
            ]);

            $mailData = new ForgetPasswordMailDto(
                token: $token,
                email: $email,
            );

            Mail::to($email)->queue(new ApplicationMail(
                localView: 'forgetPassword',
                localSubject: 'Reset your Password',
                data: $mailData,
            ));

            return true;
        } catch (Throwable $throwable) {
            self::logException($throwable, 'Caught Exception when attempting to forget the password for '.$email, ['email' => $email]);

            throw new PasswordServiceException('Unable to forget the password');
        }
    }

    /**
     * @throws PasswordServiceException
     */
    public function resetPassword(ResetPasswordDto $dto): bool
    {
        try {
            self::logInfo('Attempting to reset the password for '.$dto->email, [
                'email' => $dto->email,
            ]);

            $resetPassword = PasswordResetToken::query()->where('email', $dto->email)->first();

            if ($resetPassword->expires_at < now()) {
                throw new PasswordServiceException('The password reset token has expired', code: Response::HTTP_FORBIDDEN);
            }

            if (! Hash::check($dto->token, $resetPassword->token)) {
                throw new PasswordServiceException('The password reset token is invalid', code: Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            User::query()->where('email', $dto->email)
                ->update($dto->toArray());

            $resetPassword->delete();

            return true;
        } catch (Throwable $throwable) {
            if ($throwable instanceof PasswordServiceException) {
                throw $throwable;
            }

            self::logException($throwable, 'Caught Exception when attempting to reset the password for '.$dto->email, ['email' => $dto->email]);

            throw new PasswordServiceException('Unable to reset the password');
        }
    }

    public function changePassword(ChangePasswordDto $dto, User $attemptingUser): bool
    {
        try {
            self::logInfo('Attempting to change password');

            if ($attemptingUser->id !== self::getLoggedInUser()->id) {
                throw new PasswordServiceException("you aren't authorized to perform this action", Response::HTTP_FORBIDDEN);
            }

            if (! Hash::check($dto->currentPassword, $attemptingUser->password)) {
                throw new PasswordServiceException("current Password doesn't match provided password", Response::HTTP_FORBIDDEN);
            }

            $attemptingUser->update($dto->toArray());
            $attemptingUser->tokens()->delete();
            $attemptingUser->refreshToken()->delete();

            return true;
        } catch (Throwable $throwable) {
            if ($throwable instanceof PasswordServiceException) {
                throw $throwable;
            }

            self::logException($throwable, 'Caught Exception when attempting to change the password');

            throw new PasswordServiceException('Unable to change the password');
        }
    }
}
