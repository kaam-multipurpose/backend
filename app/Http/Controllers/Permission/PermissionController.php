<?php

declare(strict_types=1);

namespace App\Http\Controllers\Permission;

use App\Exceptions\PermissionServiceException;
use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionCategoryResource;
use App\Services\Contracts\PermissionServiceContract;
use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Http\JsonResponse;

final class PermissionController extends Controller
{
    use HasAuthenticatedUser;
    use HasLogger;

    public function __construct(private PermissionServiceContract $permissionService) {}

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
