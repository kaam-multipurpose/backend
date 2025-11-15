<?php

declare(strict_types=1);

namespace App\Services\Unit;

use App\Repositories\UnitRepository;
use App\Services\Contracts\UnitServiceContract;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;

final class UnitService implements UnitServiceContract
{
    use HasAuthenticatedUser, HasLogger;

    public function __construct(
        private readonly UnitRepository $unitRepository,
    ) {}

    // Your service logic goes here

}
