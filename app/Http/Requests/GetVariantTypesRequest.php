<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\PermissionsEnum;
use App\Http\Requests\Abstract\AbstractGetPaginatedRequest;

final class GetVariantTypesRequest extends AbstractGetPaginatedRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can(PermissionsEnum::ADD_PRODUCT);
    }
}
