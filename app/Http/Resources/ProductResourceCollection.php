<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductResourceCollection extends ResourceCollection
{
    public function __construct($resource, public bool $isMax = false)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection
            ->map(fn($variant) => new ProductResource($variant, $this->isMax)->toArray($request)
            )->all();
    }
}
