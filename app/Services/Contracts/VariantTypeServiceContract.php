<?php

namespace App\Services\Contracts;

use App\Dto\AddValuesToVariantTypeDto;
use App\Dto\AddVariantTypeDto;
use App\Dto\GetPaginatedVariantTypesDto;
use App\Exceptions\VariantTypeServiceException;
use App\Models\VariantType;
use App\Models\VariantTypeValue;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface VariantTypeServiceContract
{
    /**
     * @throws VariantTypeServiceException
     */
    public function addVariantType(AddVariantTypeDto $dto): VariantType;

    /**
     * @throws VariantTypeServiceException
     */
    public function getVariantTypes(GetPaginatedVariantTypesDto $dto): LengthAwarePaginator;

    /**
     * @throws VariantTypeServiceException
     */
    public function addValuesToVariantType(AddValuesToVariantTypeDto $dto): Collection;

    /**
     * @throws VariantTypeServiceException
     */
    public function deleteVariantType(VariantType $variantType): void;

    /**
     * @throws VariantTypeServiceException
     */
    public function deleteVariantTypeValue(VariantType $variantType, VariantTypeValue $variantTypeValue): void;
}
