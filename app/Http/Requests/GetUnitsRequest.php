<?php

namespace App\Http\Requests;

use App\Enums\PermissionsEnum;
use App\Http\Requests\Abstract\AbstractGetPaginatedRequest;


class GetUnitsRequest extends AbstractGetPaginatedRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can(PermissionsEnum::VIEW_UNIT);
    }
}
