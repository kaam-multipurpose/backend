<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\PermissionCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

final class PermissionCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        /** @var PermissionCategory $category */
        $category = $this->resource;

        return [
            'name' => $category->name,
            'permissions' => $category->permissions->pluck('name')->toArray(),
        ];
    }
}
