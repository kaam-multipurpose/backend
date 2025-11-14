<?php

declare(strict_types=1);

namespace App\Dto\Mail;

final readonly class ForgetPasswordMailDto
{
    public function __construct(
        public string $token,
        public string $email,
    ) {}
}
