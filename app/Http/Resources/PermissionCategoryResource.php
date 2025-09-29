<?php

namespace App\Http\Resources;

use App\Models\PermissionCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var PermissionCategory $category */
        $category = $this->resource;

        return [
            'name' => $category->name,
            'permissions' => PermissionResource::collection($category->permissions),
        ];
    }
}
