<?php

namespace App\Utils\Seeders;

use App\Enum\PermissionsEnum;
use App\Enum\UserRolesEnum;
use App\Utils\Logger\Dto\LoggerContextDto;
use App\Utils\Logger\Logger;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeederUtil
{
    public static function run(): void
    {
        self::seedRoles();
        self::seedPermission();
        self::assignPermissionToRole();
    }

    public static function seedRoles(): void
    {
        self::syncEnumToModel(UserRolesEnum::values(), Role::class);
    }

    protected static function syncEnumToModel(array $enumValues, string $modelClass): void
    {
        try {
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
        } catch (\Throwable $e) {
            Logger::error($e->getMessage(), LoggerContextDto::fromException($e, extra: [
                "info" => "When syncing enum to model {$modelClass}"
            ]));
        }
    }

    public static function seedPermission(): void
    {
        self::syncEnumToModel(PermissionsEnum::values(), Permission::class);
    }

    public static function assignPermissionToRole(): void
    {
        $currentRole = "";
        try {
            $definedRoles = array_filter(UserRolesEnum::cases(), fn(UserRolesEnum $role) => $role !== UserRolesEnum::SUPER_ADMIN);

            collect($definedRoles)->each(function (UserRolesEnum $roleEnum) use (&$currentRole) {
                $role = Role::where(['name' => $roleEnum->value])->first();
                $currentRole = $role;
                $permissions = array_map(fn($p) => $p->value, $roleEnum->permissions());
                $role->syncPermissions($permissions);
            });
        } catch (\Throwable $e) {
            Logger::error($e->getMessage(), LoggerContextDto::fromException($e, extra: [
                "info" => "When assigning permission to role {$currentRole}"
            ]));
        }
    }

}
