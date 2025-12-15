<?php

declare(strict_types=1);

namespace App\Services\Unit;

use App\Dtos\AddUnitDto;
use App\Dtos\GetPaginatedUnitsDto;
use App\Dtos\UpdateUnitDto;
use App\Exceptions\UnitServiceException;
use App\Models\Unit;
use App\Repositories\UnitRepository;
use App\Services\Contracts\UnitServiceContract;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class UnitService implements UnitServiceContract
{
    use HasAuthenticatedUser, HasLogger;

    public function __construct(
        private readonly UnitRepository $unitRepository,
    ) {
    }

    public function addUnit(AddUnitDto $dto): Unit
    {
        self::logInfo('Attempt to add unit');

        return $this->unitRepository->addUnit($dto->toArray());
    }

    public function getUnits(GetPaginatedUnitsDto $dto): LengthAwarePaginator
    {
        self::logInfo('Attempt to get units');

        return $this->unitRepository->getPaginatedUnits($dto);
    }

    public function updateUnit(UpdateUnitDto $dto, Unit $unit): Unit
    {
        self::logInfo('Attempt to update unit');

        return $this->unitRepository->updateUnit($dto, $unit);
    }

    public function deleteUnit(Unit $unit): bool
    {
        self::logInfo('Attempt to delete unit');

        return $this->unitRepository->deleteUnit($unit);
    }
}
