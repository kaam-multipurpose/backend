<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductUnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "name" => $this->name,
            "multiplier" => $this->multiplier,
            "conversion_rate" => $this->conversion_rate,
            "is_base" => $this->is_base,
            "is_max" => $this->is_max,
        ];
    }
}
