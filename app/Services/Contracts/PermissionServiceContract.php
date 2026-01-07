<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface PermissionServiceContract
{
    public function getAllPermissions(): Collection;
}
