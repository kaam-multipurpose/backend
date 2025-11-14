<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\VariantType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

final class VariantTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        /** @var VariantType $variantType */
        $variantType = $this->resource;

        return [
            'id' => $variantType->id,
            'name' => $variantType->name,
            'slug' => $variantType->slug,
            'values' => VariantTypeValueResource::collection($variantType->variantTypeValues),
        ];
    }
}
