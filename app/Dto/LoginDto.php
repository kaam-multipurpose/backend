<?php

namespace App\Dto;

use App\Dto\Contract\DtoContract;
use Illuminate\Http\Request;

final readonly class LoginDto implements DtoContract
{

    public function __construct(public string $email, public string $password)
    {

    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            email: $request->input('email'),
            password: $request->input('password'),
        );
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
