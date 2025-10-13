<?php

namespace App\Services\Permission;

use App\Exceptions\PermissionServiceException;
use App\Models\PermissionCategory;
use App\Services\Contracts\PermissionServiceContract;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

class PermissionService implements PermissionServiceContract
{
    use HasAuthenticatedUser, HasLogger;

    public function __construct() {}

    // Your service logic goes here

    /**
     * @throws PermissionServiceException
     */
    public function getAllPermissions(): Collection
    {

        try {
            self::logInfo('Attempt to get all permissions');

            return PermissionCategory::query()->with('permissions')->get();
        } catch (Throwable $e) {
            self::logException($e, 'Caught Exception when getting permissions');
            throw new PermissionServiceException('Unable to get all permissions');
        }
    }
}
