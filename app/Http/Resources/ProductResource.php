<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function __construct($resource, public bool $isMax = false, public bool $withPrice = false)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $product = [
            "name" => $this->name,
            "slug" => $this->slug,
            "variant_type" => $this->variant_type
        ];
        if ($this->isMax) {
            $product += [
                "units" => ProductUnitResource::collection($this->units),
                "variants" => VariantResource::collection($this->variants)
            ];
        }

        return $product;
    }
}
