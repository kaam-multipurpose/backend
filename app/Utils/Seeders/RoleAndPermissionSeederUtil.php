<?php

declare(strict_types=1);

namespace App\Utils\Seeders;

use App\Enums\PermissionsEnum;
use App\Enums\UserRolesEnum;
use App\Models\Permission;
use App\Models\PermissionCategory;
use App\Models\Role;
use App\Utils\Logger\Dto\LoggerContextDto;
use App\Utils\Logger\Logger;
use Exception;
use Illuminate\Support\Str;
use Throwable;

final class RoleAndPermissionSeederUtil
{
    /**
     * @throws Exception
     */
    public static function run(): void
    {
        self::seedRoles();
        self::seedPermissionsCategories();
        self::seedPermission();
        self::assignPermissionToRole();
    }

    /**
     * @throws Exception
     */
    public static function seedRoles(): void
    {
        self::syncEnumToModel(UserRolesEnum::values(), Role::class);
    }

    /**
     * @throws Exception
     */
    public static function seedPermission(): void
    {
        self::syncEnumToModel(PermissionsEnum::values(), Permission::class);
    }

    /**
     * @throws Exception
     */
    public static function assignPermissionToRole(): void
    {
        try {
            $definedRoles = array_filter(
                UserRolesEnum::cases(),
                fn (UserRolesEnum $role): bool => $role !== UserRolesEnum::SUPER_ADMIN
            );

            $roleNames = collect($definedRoles)->pluck('value')->toArray();
            $roles = Role::query()->whereIn('name', $roleNames)->get()->keyBy('name');

            collect($definedRoles)->each(function (UserRolesEnum $roleEnum) use ($roles): void {
                $role = $roles->get($roleEnum->value);

                if (! $role) {
                    Logger::warning('Role not found: '.$roleEnum->value);

                    return;
                }

                $permissions = array_map(fn ($p) => $p->value, $roleEnum->permissions());
                $role->syncPermissions($permissions);
            });

        } catch (Throwable $throwable) {
            Logger::error($throwable->getMessage(), LoggerContextDto::fromException($throwable, extra: [
                'info' => 'When assigning permissions to roles',
            ]));
            throw new Exception('Unable to sync permission to role', $throwable->getCode(), $throwable);
        }
    }

    /**
     * @throws Exception
     */
    public static function seedPermissionsCategories(): void
    {
        try {
            $existingCategories = PermissionCategory::query()->pluck('name')->toArray();
            $allCategories = self::generatePermissionsCategories(PermissionsEnum::values());
            $missingCategories = array_diff($allCategories, $existingCategories);

            if ($missingCategories !== []) {
                $now = now();
                $data = collect($missingCategories)->map(fn ($category): array => [
                    'name' => $category,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->toArray();

                PermissionCategory::query()->insert($data);
            }
        } catch (Throwable $throwable) {
            Logger::error($throwable->getMessage(), LoggerContextDto::fromException($throwable, extra: [
                'info' => 'When inserting permission categories',
            ]));
            throw new Exception('Unable to insert permission categories', $throwable->getCode(), $throwable);
        }
    }

    private static function generatePermissionsCategories(array $enumValues): array
    {
        $permissionCategories = [];

        foreach ($enumValues as $enumValue) {
            $categoryName = self::generatePermissionCategory($enumValue);
            if (! in_array($categoryName, $permissionCategories)) {
                $permissionCategories[] = $categoryName;
            }
        }

        return $permissionCategories;
    }

    private static function generatePermissionCategory(string $name): string
    {
        $userCategoryNames = ['Admin', 'Rep'];
        $prefix = 'Permission';

        $permissionNames = explode('-', $name);
        $categoryName = ucfirst(array_pop($permissionNames));

        if (in_array($categoryName, $userCategoryNames)) {
            $categoryName = 'User';
        }

        return $categoryName.' '.$prefix;
    }

    /**
     * @throws Exception
     */
    private static function syncEnumToModel(array $enumValues, string $modelClass): void
    {
        try {
            $existingNames = $modelClass::pluck('name')->toArray();
            $missing = array_diff($enumValues, $existingNames);

            if ($missing === []) {
                return;
            }

            $permissionCategories = [];
            if ($modelClass === Permission::class) {
                $permissionCategories = PermissionCategory::query()
                    ->select(['name', 'id'])
                    ->get()
                    ->keyBy('name');
            }

            $now = now();
            $rows = collect($missing)->map(function ($value) use ($now, $modelClass, $permissionCategories): array {
                $data = [
                    'name' => $value,
                    'guard_name' => 'api',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if ($modelClass === Permission::class) {
                    $categoryName = self::generatePermissionCategory($value);
                    $category = $permissionCategories->get($categoryName);
                    $data['permission_category_id'] = $category?->id;
                }

                if ($modelClass === Role::class) {
                    $data['slug'] = Str::slug($value);
                }

                return $data;
            })->toArray();

            $modelClass::insert($rows);

        } catch (Throwable $throwable) {
            Logger::error($throwable->getMessage(), LoggerContextDto::fromException($throwable, extra: [
                'info' => 'When syncing enum to model '.$modelClass,
            ]));
            throw new Exception('Unable to sync enum to model '.$modelClass, $throwable->getCode(), $throwable);
        }
    }
}
