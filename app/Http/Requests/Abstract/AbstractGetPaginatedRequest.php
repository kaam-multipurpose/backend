<?php

declare(strict_types=1);

namespace App\Http\Requests\Abstract;

use App\Enums\PaginationEnum;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractGetPaginatedRequest extends FormRequest
{
    abstract public function authorize(): bool;

    public function rules(): array
    {
        return PaginationEnum::rules();
    }
}
