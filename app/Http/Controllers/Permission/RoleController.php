<?php

declare(strict_types=1);

namespace App\Http\Controllers\Permission;

use App\Dto\AddRoleDto;
use App\Exceptions\RoleServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddRoleRequest;
use App\Http\Requests\EditRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Services\Contracts\RoleServiceContract;
use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class RoleController extends Controller
{
    use HasAuthenticatedUser;
    use HasLogger;

    public function __construct(private readonly RoleServiceContract $roleService) {}

    /**
     * @throws Throwable
     * @throws RoleServiceException
     */
    public function addRole(AddRoleRequest $request): JsonResponse
    {
        $newRole = $this->roleService->addRole(
            AddRoleDto::fromValidated($request->validated()),
        );

        self::logInfo('Role Added Successfully');

        return ApiResponse::success(
            new RoleResource($newRole, full: true),
            'Role Added Successfully',
            status: Response::HTTP_CREATED
        );

    }

    /**
     * @throws RoleServiceException
     */
    public function editRolePermission(EditRoleRequest $request, Role $role): JsonResponse
    {

        $this->roleService->editRolePermission(
            $role,
            $request->validated()['permissions']
        );

        self::logInfo('Role Edited Successfully');

        return ApiResponse::success(
            message: 'Role Updated Successfully',
            status: Response::HTTP_OK
        );
    }

    /**
     * @throws RoleServiceException
     */
    public function deleteRole(Request $request, Role $role): JsonResponse
    {
        $this->roleService->deleteRole($role);

        self::logInfo('Role Deleted Successfully');

        return ApiResponse::success(
            message: 'Role Deleted Successfully',
            status: Response::HTTP_OK
        );
    }

    /**
     * @throws RoleServiceException
     */
    public function getRoles(): JsonResponse
    {
        $roles = $this->roleService->getRoles();

        return ApiResponse::success(
            RoleResource::collection($roles),
            'Roles retrieved successfully',
        );
    }

    /**
     * @throws RoleServiceException
     */
    public function getRole(Request $request, Role $role): JsonResponse
    {
        $role = $this->roleService->getRole($role);

        return ApiResponse::success(
            new RoleResource($role, true),
            'Roles retrieved successfully',
        );
    }
}
