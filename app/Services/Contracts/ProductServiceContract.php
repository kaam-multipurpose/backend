<?php

namespace App\Services\Contracts;

use App\Dto\CreateProductDto;
use App\Exceptions\ProductServiceException;
use App\Models\Product;

interface ProductServiceContract extends GenericServiceContract
{
    /**
     * @param CreateProductDto $createProductDto
     * @return Product
     * @throws ProductServiceException
     */
    public function createProduct(CreateProductDto $createProductDto): Product;
}
