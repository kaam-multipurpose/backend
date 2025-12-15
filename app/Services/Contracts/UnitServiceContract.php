<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Dtos\AddUnitDto;
use App\Dtos\GetPaginatedUnitsDto;
use App\Dtos\UpdateUnitDto;
use App\Models\Unit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UnitServiceContract
{
    public function addUnit(AddUnitDto $dto): Unit;

    public function getUnits(GetPaginatedUnitsDto $dto): LengthAwarePaginator;

    public function updateUnit(UpdateUnitDto $dto, Unit $unit): Unit;

    public function deleteUnit(Unit $unit): bool;
}
