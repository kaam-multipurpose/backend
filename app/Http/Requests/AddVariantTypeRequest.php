<?php

namespace App\Http\Requests;

use App\Enum\PermissionsEnum;
use Illuminate\Foundation\Http\FormRequest;

class AddVariantTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can(PermissionsEnum::ADD_PRODUCT);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:20', 'unique:variant_types,name'],
            'values' => ['required', 'array', 'min:1'],
            'values.*' => ['required', 'string', 'min:3', 'max:20'],
        ];
    }
}
