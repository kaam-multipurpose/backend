<?php

namespace App\Http\Resources;

use App\Models\VariantTypeValue;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariantTypeValueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        /** @var VariantTypeValue $variantTypeValue */
        $variantTypeValue = $this->resource;

        return [
            'id' => $variantTypeValue->id,
            'name' => $variantTypeValue->name,
            'slug' => $variantTypeValue->slug,
        ];
    }
}
