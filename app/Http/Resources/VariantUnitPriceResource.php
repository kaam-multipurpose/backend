<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariantUnitPriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $unit = $this->productUnit;
        return [
            "unit" => new ProductUnitResource($unit)->name,
            "selling_price" => $this->selling_price
        ];
    }
}
