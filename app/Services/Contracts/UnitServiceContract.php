<?php

namespace App\Services\Contracts;

use App\Dto\createUnitDto;
use App\Exceptions\UnitServiceException;
use App\Models\Unit;

interface UnitServiceContract extends GenericServiceContract
{

    /**
     * @param createUnitDto $createUnitDto
     * @return Unit
     * @throws UnitServiceException
     */
    public function createUnit(CreateUnitDto $createUnitDto): Unit;
}
