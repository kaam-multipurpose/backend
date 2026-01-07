<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Dtos\AddRoleDto;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

interface RoleServiceContract
{
    public function addRole(AddRoleDto $dto): Role;

    public function editRolePermission(Role $role, array $permissions): bool;

    public function deleteRole(Role $role): bool;

    public function getRoles(): Collection;

    public function getRole(Role $role): Role;
}
