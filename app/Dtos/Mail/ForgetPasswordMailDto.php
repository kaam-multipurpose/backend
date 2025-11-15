<?php

declare(strict_types=1);

namespace App\Dtos\Mail;

final readonly class ForgetPasswordMailDto
{
    public function __construct(
        public string $token,
        public string $email,
    ) {}
}
