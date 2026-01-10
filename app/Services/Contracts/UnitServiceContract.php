<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Dtos\AddUnitDto;
use App\Dtos\GetPaginatedUnitsDto;
use App\Dtos\UpdateUnitDto;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Collection;

interface UnitServiceContract
{
    public function addUnit(AddUnitDto $dto): Unit;

    public function getUnits(): Collection;

    public function updateUnit(UpdateUnitDto $dto, Unit $unit): Unit;

    public function deleteUnit(Unit $unit): bool;
}
