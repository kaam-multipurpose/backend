<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;

abstract class Controller
{
    use HasAuthenticatedUser;
    use HasLogger;
}
