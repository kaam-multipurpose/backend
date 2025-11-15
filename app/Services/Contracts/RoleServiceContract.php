<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Dtos\AddRoleDto;
use App\Exceptions\RoleServiceException;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

interface RoleServiceContract
{
    /**
     * @throws RoleServiceException
     */
    public function addRole(AddRoleDto $dto): Role;

    /**
     * @param  string[]  $permissions
     *
     * @throws RoleServiceException
     */
    public function editRolePermission(Role $role, array $permissions): bool;

    /**
     * @throws RoleServiceException
     */
    public function deleteRole(Role $role): bool;

    /**
     * @throws RoleServiceException
     */
    public function getRoles(): Collection;

    /**
     * @throws RoleServiceException
     */
    public function getRole(Role $role): Role;
}
