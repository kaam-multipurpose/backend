<?php

namespace App\Utils\Seeders;

use App\Enum\PermissionsEnum;
use App\Enum\UserRolesEnum;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeederUtil
{
    public static function run():void
    {
        self::seedRoles();
        self::seedPermission();
        self::assignPermissionToRole();
    }
    public static function seedPermission(): void
    {
        self::syncEnumToModel(PermissionsEnum::values(), Permission::class);
    }

    public static function seedRoles(): void
    {
        self::syncEnumToModel(UserRolesEnum::values(), Role::class);
    }

    public static function assignPermissionToRole(): void
    {
        $definedRoles = array_filter(UserRolesEnum::cases(), fn ($name) => $name !== UserRolesEnum::SUPER_ADMIN);

        collect($definedRoles)->each(function (UserRolesEnum $roleEnum){
            $role = Role::where(['name' => $roleEnum->value])->first();
            $permissions = array_map(fn($p)=>$p->value, $roleEnum->permissions());
            $role->syncPermissions($permissions);
        });
    }

    protected static function syncEnumToModel(array $enumValues, string $modelClass): void
    {
        $existingNames = $modelClass::pluck('name')->toArray();

        $missing = array_diff($enumValues, $existingNames);

        if (!empty($missing)) {
            $now = now();

            $rows = collect($missing)->map(fn($value) => [
                'name' => $value,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ])->toArray();

            $modelClass::insert($rows);
        }
    }

}
