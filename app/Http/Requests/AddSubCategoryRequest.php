<?php

namespace App\Http\Requests;

use App\Enum\PermissionsEnum;
use Illuminate\Foundation\Http\FormRequest;

class AddSubCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can(PermissionsEnum::ADD_CATEGORY);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'unique:categories,name'],
            'has_additional_variant_type' => ['required', 'boolean'],
            'variant_type_ids' => ['required_if_accepted:has_additional_variant_type', 'array', 'min:1'],
            'variant_type_ids.*' => ['integer', 'exists:variant_types,id'],
        ];
    }
}
