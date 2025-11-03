<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    private bool $expanded = false;

    public function isExpanded(): self
    {
        $this->expanded = true;

        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /**
         * @var Category $category
         */
        $category = $this->resource;

        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'variant_type_count' => $category->allVariantTypes->count(),
            'parent_category' => $this->when(
                ! is_null($category->parent_id),
                $category->category?->name,
            ),
            'sub_category_count' => $this->when(
                is_null($category->parent_id),
                $category->subCategories->count()
            ),
            'variant_types' => $this->when(
                $this->expanded,
                VariantTypeResource::collection($category->allVariantTypes),
            ),
            'sub_categories' => $this->when(
                is_null($category->parent_id) && $this->expanded,
                CategoryResource::collection($category->subCategories)
            ),
        ];
    }
}
