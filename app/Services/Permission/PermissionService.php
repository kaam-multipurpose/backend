<?php

declare(strict_types=1);

namespace App\Services\Permission;

use App\Exceptions\PermissionServiceException;
use App\Models\PermissionCategory;
use App\Services\Contracts\PermissionServiceContract;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

final class PermissionService implements PermissionServiceContract
{
    use HasAuthenticatedUser;
    use HasLogger;

    // Your service logic goes here

    /**
     * @throws PermissionServiceException
     */
    public function getAllPermissions(): Collection
    {
        self::logInfo('Attempt to get all permissions');

        return PermissionCategory::query()->with('permissions')->get();
    }
}
