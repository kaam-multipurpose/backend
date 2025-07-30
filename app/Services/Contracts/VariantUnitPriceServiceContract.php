<?php

namespace App\Services\Contracts;

use App\Exceptions\VariantUnitPriceServiceException;
use App\Models\Product;

interface VariantUnitPriceServiceContract extends GenericServiceContract
{
    /**
     * @param Product $product
     * @return bool
     * @throws VariantUnitPriceServiceException
     */
    public function createPrice(Product $product): bool;
}
