<?php

namespace App\Http\Requests;

use App\Enum\PermissionsEnum;
use Illuminate\Foundation\Http\FormRequest;

class EditRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can(PermissionsEnum::EDIT_ROLE);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['string', 'distinct', 'exists:permissions,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'permissions.min' => 'Please select at least one permission.',
            'permissions.*.distinct' => 'You have selected the same permission multiple times.',
            'permissions.*.exists' => 'The permission ":input" does not exist in the system.',
        ];
    }
}
