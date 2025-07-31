<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            "name" => $this->name,
            "slug" => $this->slug,
            "cost_price" => $this->cost_price,
            "selling_prices" => VariantUnitPriceResource::collection($this->unitPrices)
        ];
    }
}
