<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enum\PaginationEnum;
use App\Enum\PermissionsEnum;
use Illuminate\Foundation\Http\FormRequest;

final class GetCategoriesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can(PermissionsEnum::VIEW_CATEGORY);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return PaginationEnum::rules();
    }
}
