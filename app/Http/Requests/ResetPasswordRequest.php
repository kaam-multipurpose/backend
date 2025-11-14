<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

final class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'password' => ['required', 'min:6', 'confirmed'],
            'email' => ['required', 'email', 'exists:password_reset_tokens', 'email'],
            'token' => ['required', 'string'],

        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'email.exists' => 'No password reset request found for this email.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => $this->route('email'),
            'token' => $this->route('token'),
        ]);

    }
}
