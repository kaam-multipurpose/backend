<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Dtos\ChangePasswordDto;
use App\Dtos\ResetPasswordDto;
use App\Models\User;

interface PasswordServiceContract
{
    public function forgetPassword(string $email): bool;

    public function resetPassword(ResetPasswordDto $dto): bool;

    public function changePassword(ChangePasswordDto $dto, User $attemptingUser): bool;
}
