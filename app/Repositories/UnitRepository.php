<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dtos\GetPaginatedUnitsDto;
use App\Dtos\UpdateUnitDto;
use App\Models\Unit;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class UnitRepository
{
    public function addUnit(array $data): Unit
    {
        return Unit::query()->create($data);
    }

    public function getPaginatedUnits(GetPaginatedUnitsDto $dto): LengthAwarePaginator
    {
        return Unit::query()->paginate(
            perPage: $dto->row,
            page: $dto->page,
        );
    }

    public function deleteUnit(Unit $unit): bool
    {
        return $unit->delete();
    }

    public function updateUnit(UpdateUnitDto $dto, Unit $unit): Unit
    {
        $filledArray = $dto->toFilledArray();
        if (empty($filledArray)) {
            throw new Exception('Attempt to update unit with empty records.');
        }

        $unit->update($filledArray);

        return $unit;
    }
}
