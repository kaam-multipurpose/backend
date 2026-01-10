<?php

declare(strict_types=1);

namespace App\Services\Unit;

use App\Dtos\AddUnitDto;
use App\Dtos\GetPaginatedUnitsDto;
use App\Dtos\UpdateUnitDto;
use App\Models\Unit;
use App\Repositories\UnitRepository;
use App\Services\AbstractService;
use App\Services\Contracts\UnitServiceContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class UnitService extends AbstractService implements UnitServiceContract
{
    public function __construct(
        private readonly UnitRepository $unitRepository,
    ) {
    }

    public function addUnit(AddUnitDto $dto): Unit
    {
        self::logInfo('Attempt to add unit');

        return $this->unitRepository->addUnit($dto->toArray());
    }

    public function getUnits(): Collection
    {
        self::logInfo('Attempt to get units');

        return $this->unitRepository->getPaginatedUnits();
    }

    public function updateUnit(UpdateUnitDto $dto, Unit $unit): Unit
    {
        self::logInfo('Attempt to update unit');

        $filledArray = $dto->toFilledArray();

        if (empty($filledArray)) {
            return $unit;
        }

        return $this->unitRepository->updateUnit($filledArray, $unit);
    }

    public function deleteUnit(Unit $unit): bool
    {
        self::logInfo('Attempt to delete unit');

        return $this->unitRepository->deleteUnit($unit);
    }
}
