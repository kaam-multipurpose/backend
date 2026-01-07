<?php

declare(strict_types=1);

namespace App\Services\Permission;

use App\Dtos\AddRoleDto;
use App\Enums\UserRolesEnum;
use App\Exceptions\ApplicationException;
use App\Exceptions\RoleServiceException;
use App\Models\Role;
use App\Services\AbstractService;
use App\Services\Contracts\RoleServiceContract;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class RoleService extends AbstractService implements RoleServiceContract
{

    public function addRole(AddRoleDto $dto): Role
    {
        self::logInfo('Attempt to add role');

        return DB::transaction(function () use ($dto) {
            $role = Role::query()->create([
                'name' => $dto->role,
                'guard_name' => 'api',
            ]);

            $role->syncPermissions($dto->permissions);

            return $role;
        });

    }

    public function editRolePermission(Role $role, array $permissions): bool
    {
        self::logInfo('Attempt to edit role');

        $role->syncPermissions($permissions);

        return true;
    }

    public function deleteRole(Role $role): bool
    {
        self::logInfo('Attempt to delete role');
        $definedRole = UserRolesEnum::values();
        if (in_array($role->name, $definedRole)) {
            throw new ApplicationException(
                'Cannot delete predefined roles',
                Response::HTTP_FORBIDDEN
            );
        }
        $role->delete();
        return true;
    }

    public function getRoles(): Collection
    {
        return Role::all();
    }

    public function getRole(Role $role): Role
    {
        return $role;
    }
}
