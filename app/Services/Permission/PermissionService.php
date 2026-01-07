<?php

declare(strict_types=1);

namespace App\Services\Permission;

use App\Models\PermissionCategory;
use App\Services\AbstractService;
use App\Services\Contracts\PermissionServiceContract;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

final class PermissionService extends AbstractService implements PermissionServiceContract
{
    public function getAllPermissions(): Collection
    {
        self::logInfo('Attempt to get all permissions');

        return PermissionCategory::query()->with('permissions')->get();
    }
}
