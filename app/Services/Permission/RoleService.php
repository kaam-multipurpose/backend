<?php

declare(strict_types=1);

namespace App\Services\Permission;

use App\Dto\AddRoleDto;
use App\Enum\UserRolesEnum;
use App\Exceptions\RoleServiceException;
use App\Models\Role;
use App\Services\Contracts\RoleServiceContract;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class RoleService implements RoleServiceContract
{
    use HasAuthenticatedUser;
    use HasLogger;

    /**
     * @throws RoleServiceException
     * @throws Throwable
     */
    public function addRole(AddRoleDto $dto): Role
    {
        try {
            self::logInfo('Attempt to add role');

            return DB::transaction(function () use ($dto) {
                $role = Role::query()->create([
                    'name' => $dto->role,
                    'guard_name' => 'api',
                ]);

                $role->syncPermissions($dto->permissions);

                return $role;
            });

        } catch (Throwable $throwable) {
            self::logException($throwable, 'Caught Exception when adding role');
            throw new RoleServiceException('Unable to add role');
        }
    }

    /**
     * @param  string[]  $permissions
     *
     * @throws RoleServiceException
     */
    public function editRolePermission(Role $role, array $permissions): bool
    {
        try {
            self::logInfo('Attempt to edit role');

            $role->syncPermissions($permissions);

            return true;
        } catch (Throwable $throwable) {
            self::logException($throwable, 'Caught Exception when editing role');
            throw new RoleServiceException('Unable to edit role');
        }
    }

    /**
     * @throws RoleServiceException
     */
    public function deleteRole(Role $role): bool
    {
        try {
            self::logInfo('Attempt to delete role');
            $definedRole = UserRolesEnum::values();
            if (in_array($role->name, $definedRole)) {
                throw new RoleServiceException(
                    'Cannot delete predefined roles',
                    Response::HTTP_FORBIDDEN
                );
            }

            $role->delete();

            return true;
        } catch (Throwable $throwable) {
            self::logException($throwable, 'Caught Exception when deleting role');
            if ($throwable instanceof RoleServiceException) {
                throw $throwable;
            }

            throw new RoleServiceException('Unable to delete role');
        }
    }

    public function getRoles(): Collection
    {
        try {
            return Role::all();
        } catch (Throwable $throwable) {
            self::logException($throwable, 'Caught Exception when getting all roles');

            throw new RoleServiceException('Unable to all roles');
        }
    }

    public function getRole(Role $role): Role
    {
        try {
            return $role;
        } catch (Throwable $throwable) {
            self::logException($throwable, 'Caught Exception when getting a role');

            throw new RoleServiceException('Unable to a role');
        }
    }
}
