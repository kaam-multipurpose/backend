<?php

declare(strict_types=1);

namespace App\Services\VariantType;

use App\Dtos\AddValuesToVariantTypeDto;
use App\Dtos\AddVariantTypeDto;
use App\Dtos\AddVariantTypeValueDto;
use App\Dtos\GetPaginatedVariantTypesDto;
use App\Exceptions\ApplicationException;
use App\Exceptions\VariantTypeServiceException;
use App\Models\VariantType;
use App\Services\AbstractService;
use App\Services\Contracts\VariantTypeServiceContract;
use App\Services\VariantType\Trait\HasVariantTypeValue;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

final class VariantTypeService extends AbstractService implements VariantTypeServiceContract
{
    use HasVariantTypeValue;

    public function addVariantType(AddVariantTypeDto $dto): VariantType
    {
        self::logInfo('Attempt to add variant type');

        return DB::transaction(function () use ($dto) {
            $variantType = VariantType::query()->create($dto->toArray());

            $variantTypeValues = $this->generateDBVariantTypeValue($dto->values);

            $variantType->variantTypeValues()->createMany($variantTypeValues);

            return $variantType;
        });
    }

    private function generateDBVariantTypeValue(array $array): array
    {
        if (!collect($array)->every(fn($value): true => $value instanceof AddVariantTypeValueDto)) {
            throw new ApplicationException('Expected an array of AddVariantTypeValueDto, found invalid item.');
        }

        return collect($array)
            ->map(fn(AddVariantTypeValueDto $value): array => $value->toArray())->toArray();
    }

    public function getVariantTypes(GetPaginatedVariantTypesDto $dto): LengthAwarePaginator
    {
        self::logInfo('Attempt to get variant types');

        return VariantType::query()->with('variantTypeValues')->paginate(
            perPage: $dto->row,
            page: $dto->page
        );
    }

    public function addValuesToVariantType(AddValuesToVariantTypeDto $dto): Collection
    {
        self::logInfo('Attempt to add values variant types');

        $variantTypeValues = $this->generateDBVariantTypeValue($dto->values);

        return $dto->variantType->variantTypeValues()->createMany($variantTypeValues);
    }
    
    public function deleteVariantType(VariantType $variantType): void
    {
        self::logInfo('Attempt to delete variant type', [
            'variantTypeId' => $variantType->id,
        ]);

        DB::transaction(function () use ($variantType): void {
            $variantType->delete();
        });
    }
}
