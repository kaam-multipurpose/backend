<?php

namespace App\Services\Contracts;

use App\Dto\CreateProductUnitDto;
use App\Exceptions\ProductUnitServiceException;
use App\Models\Product;

interface ProductUnitServiceContract extends GenericServiceContract
{
    /**
     * @param array<CreateProductUnitDto> $createProductUnitDto
     * @param Product $product
     * @return bool
     * @throws ProductUnitServiceException
     */
    public function createProductUnits(array $createProductUnitDto, Product $product): bool;
}
