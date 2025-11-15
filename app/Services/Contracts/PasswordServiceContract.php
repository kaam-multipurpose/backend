<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Dtos\ChangePasswordDto;
use App\Dtos\ResetPasswordDto;
use App\Exceptions\PasswordServiceException;
use App\Models\User;

interface PasswordServiceContract
{
    /**
     * @throws PasswordServiceException
     */
    public function forgetPassword(string $email): bool;

    /**
     * @throws PasswordServiceException
     */
    public function resetPassword(ResetPasswordDto $dto): bool;

    /**
     * @throws PasswordServiceException
     */
    public function changePassword(ChangePasswordDto $dto, User $attemptingUser): bool;
}
