<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Dtos\AddValuesToVariantTypeDto;
use App\Dtos\AddVariantTypeDto;
use App\Dtos\GetPaginatedVariantTypesDto;
use App\Models\VariantType;
use App\Models\VariantTypeValue;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface VariantTypeServiceContract
{
    public function addVariantType(AddVariantTypeDto $dto): VariantType;

    public function getVariantTypes(GetPaginatedVariantTypesDto $dto): LengthAwarePaginator;

    public function addValuesToVariantType(AddValuesToVariantTypeDto $dto): Collection;

    public function deleteVariantType(VariantType $variantType): void;

    public function deleteVariantTypeValue(VariantType $variantType, VariantTypeValue $variantTypeValue): void;
}
