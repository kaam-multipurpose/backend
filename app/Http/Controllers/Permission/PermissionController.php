<?php

namespace App\Http\Controllers\Permission;

use App\Exceptions\PermissionServiceException;
use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionCategoryResource;
use App\Services\Contracts\PermissionServiceContract;
use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Http\JsonResponse;

class PermissionController extends Controller
{
    use HasAuthenticatedUser, HasLogger;

    public function __construct(protected PermissionServiceContract $permissionService) {}

    /**
     * @throws PermissionServiceException
     */
    public function getAllPermissions(): JsonResponse
    {
        $permissions = $this->permissionService->getAllPermissions();

        self::logInfo('Permission Retrieved Successfully');

        return ApiResponse::success(
            PermissionCategoryResource::collection($permissions),
            'All permissions'
        );
    }
}
