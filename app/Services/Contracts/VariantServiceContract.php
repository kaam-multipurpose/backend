<?php

namespace App\Services\Contracts;

use App\Dto\CreateVariantDto;
use App\Exceptions\ProductServiceException;
use App\Exceptions\VariantServiceException;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Collection;

interface VariantServiceContract extends GenericServiceContract
{
    /**
     * @param CreateVariantDto $createVariantDto
     * @param Product $product
     * @return Variant
     * @throws VariantServiceException
     */
    public function createVariant(CreateVariantDto $createVariantDto, Product $product): Variant;

    /**
     * @param array<CreateVariantDto> $variantsDto
     * @param Product $product
     * @return bool
     * @throws VariantServiceException
     */
    public function createManyVariant(array $variantsDto, Product $product): bool;
}
