<?php

namespace App\Services;

use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;

abstract class AbstractService
{
    use HasAuthenticatedUser, HasLogger;
}