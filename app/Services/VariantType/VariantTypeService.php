<?php

namespace App\Services\VariantType;

use App\Dto\AddValuesToVariantTypeDto;
use App\Dto\AddVariantTypeDto;
use App\Dto\AddVariantTypeValueDto;
use App\Dto\GetPaginatedVariantTypesDto;
use App\Exceptions\ApplicationException;
use App\Exceptions\VariantTypeServiceException;
use App\Models\VariantType;
use App\Services\Contracts\VariantTypeServiceContract;
use App\Services\VariantType\Trait\HasVariantTypeValue;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class VariantTypeService implements VariantTypeServiceContract
{
    use HasAuthenticatedUser, HasLogger, HasVariantTypeValue;

    public function __construct() {}

    /**
     * @throws VariantTypeServiceException
     * @throws Throwable
     */
    public function addVariantType(AddVariantTypeDto $dto): VariantType
    {
        try {
            self::logInfo('Attempt to add variant type');

            return DB::transaction(function () use ($dto) {
                $variantType = VariantType::query()->create($dto->toArray());

                $variantTypeValues = $this->generateDBVariantTypeValue($dto->values);

                $variantType->variantTypeValues()->createMany($variantTypeValues);

                return $variantType;
            });

        } catch (Throwable $e) {
            self::logException($e, 'Caught Exception when adding variant type');
            throw new VariantTypeServiceException('Unable to add variant type');
        }
    }

    /**
     * @throws VariantTypeServiceException
     */
    public function getVariantTypes(GetPaginatedVariantTypesDto $dto): LengthAwarePaginator
    {
        try {
            self::logInfo('Attempt to get variant types');

            return VariantType::query()->with('variantTypeValues')->paginate(
                perPage: $dto->row,
                page: $dto->page
            );

        } catch (Throwable $e) {
            self::logException($e, 'Caught Exception when getting variant types');
            throw new VariantTypeServiceException('Unable to get variant types');
        }
    }

    /**
     * @throws VariantTypeServiceException
     */
    public function addValuesToVariantType(AddValuesToVariantTypeDto $dto): Collection
    {
        try {
            self::logInfo('Attempt to add values variant types');

            $variantTypeValues = $this->generateDBVariantTypeValue($dto->values);

            return $dto->variantType->variantTypeValues()->createMany($variantTypeValues);

        } catch (Throwable $e) {
            self::logException($e, 'Caught Exception when adding values to variant types');
            throw new VariantTypeServiceException('Unable to add values to variant types');
        }

    }

    /**
     * @throws VariantTypeServiceException
     * @throws Throwable
     */
    public function deleteVariantType(VariantType $variantType): void
    {

        try {
            self::logInfo('Attempt to delete variant type', [
                'variantTypeId' => $variantType->id,
            ]);

            DB::transaction(function () use ($variantType): void {
                $variantType->delete();
            });

        } catch (Throwable $e) {
            self::logException($e, 'Caught Exception when deleting variant type', [
                'variantTypeId' => $variantType->id,
            ]);
            throw new VariantTypeServiceException('Unable to delete variant type');
        }
    }

    /**
     * @param  AddVariantTypeValueDto[]  $array
     *
     * @throws ApplicationException
     */
    private function generateDBVariantTypeValue(array $array): array
    {
        if (! collect($array)->every(fn ($value) => $value instanceof AddVariantTypeValueDto)) {
            throw new ApplicationException('Expected an array of AddVariantTypeValueDto, found invalid item.');
        }

        return collect($array)
            ->map(fn (AddVariantTypeValueDto $value) => $value->toArray())->toArray();
    }
}
