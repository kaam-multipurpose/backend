<?php

namespace App\Services;

use App\Dto\createUnitDto;
use App\Exceptions\UnitServiceException;
use App\Models\Unit;
use App\Services\Contracts\UnitServiceContract;
use App\Utils\Logger\Contract\LoggerContract;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;

class UnitService implements UnitServiceContract
{
    use HasAuthenticatedUser, HasLogger;

    public function __construct(
        protected LoggerContract $logger,
    )
    {
    }

    /**
     * @param createUnitDto $createUnitDto
     * @return Unit
     * @throws UnitServiceException
     */
    public function createUnit(CreateUnitDto $createUnitDto): Unit
    {
        try {
            $this->log("info", "creating unit");
            return Unit::create($createUnitDto->toArray());
        } catch (\Throwable $e) {
            $this->log("error", "Failed to create unit");
            throw new UnitServiceException("Failed to create product units", previous: $e);
        }
    }

}
