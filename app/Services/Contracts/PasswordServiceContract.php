<?php

namespace App\Services\Contracts;

use App\Dto\ResetPasswordDto;
use App\Exceptions\PasswordServiceException;

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
}
