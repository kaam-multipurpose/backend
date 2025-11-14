<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Exceptions\PermissionServiceException;
use Illuminate\Database\Eloquent\Collection;

interface PermissionServiceContract
{
    /**
     * @throws PermissionServiceException
     */
    public function getAllPermissions(): Collection;
}
