<?php

namespace App\Enum;

use App\Enum\Contract\EnumContract;
use App\Enum\Trait\EnumTrait;

enum UserRolesEnum: string implements EnumContract
{
    use EnumTrait;

    case ADMIN = 'admin';
    case SALES_REP = 'sales rep';
    case SUPER_ADMIN = 'super admin';

    public function permissions(): array
    {
        return match ($this) {
            self::ADMIN => [
                PermissionsEnum::ADD_PRODUCT,
                PermissionsEnum::VIEW_PRODUCT,
                PermissionsEnum::EDIT_PRODUCT,
                PermissionsEnum::DELETE_PRODUCT,
                PermissionsEnum::VIEW_CATEGORY,
                PermissionsEnum::EDIT_CATEGORY,
                PermissionsEnum::DELETE_CATEGORY,
                PermissionsEnum::MOVE_PRODUCT,
                PermissionsEnum::ADD_CATEGORY,
                PermissionsEnum::ADD_PRICE,
                PermissionsEnum::VIEW_PRICE,
                PermissionsEnum::EDIT_PRICE,
                PermissionsEnum::DELETE_PRICE,
                PermissionsEnum::VIEW_INVENTORY,
                PermissionsEnum::EDIT_INVENTORY,
                PermissionsEnum::DELETE_INVENTORY,
                PermissionsEnum::ADD_SALES_REP,
                PermissionsEnum::VIEW_SALES_REP,
                PermissionsEnum::EDIT_SALES_REP,
                PermissionsEnum::DELETE_SALES_REP,
                PermissionsEnum::ASSIGN_PERMISSIONS,
            ],

            self::SALES_REP => [
                PermissionsEnum::VIEW_PRODUCT,
                PermissionsEnum::VIEW_CATEGORY,
                PermissionsEnum::VIEW_PRICE,
                PermissionsEnum::VIEW_INVENTORY,
                PermissionsEnum::MOVE_PRODUCT,
            ],
            self::SUPER_ADMIN => PermissionsEnum::cases(),
            
            default => []
        };
    }
}
