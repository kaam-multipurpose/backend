<?php

namespace App\Enum;

use App\Enum\Contract\EnumContract;
use App\Enum\Trait\EnumTrait;

enum PermissionsEnum: string implements EnumContract
{
    use EnumTrait;

    /*
     * Product Permission
     * */
    case ADD_PRODUCT = 'add product';
    case VIEW_PRODUCT = 'view product';
    case EDIT_PRODUCT = 'edit product';
    case DELETE_PRODUCT = 'delete product';
    case ADD_CATEGORY = 'add category';
    case VIEW_CATEGORY = 'view category';
    case EDIT_CATEGORY = 'edit category';
    case DELETE_CATEGORY = 'delete category';
    case MOVE_PRODUCT = 'move product';
    case ADD_PRICE = 'add price';
    case VIEW_PRICE = 'view price';
    case EDIT_PRICE = 'edit price';
    case DELETE_PRICE = 'delete price';

    /*
     * Inventory Permission
     * */
    case ADD_INVENTORY = 'add inventory';
    case VIEW_INVENTORY = 'view inventory';
    case EDIT_INVENTORY = 'edit inventory';
    case DELETE_INVENTORY = 'delete inventory';

    /*
     * User Permission
     * */
    case ADD_ADMIN = 'add admin';
    case VIEW_ADMIN = 'view admin';
    case EDIT_ADMIN = 'edit admin';
    case DELETE_ADMIN = 'delete admin';
    case ADD_SALES_REP = 'add sales rep';
    case VIEW_SALES_REP = 'view sales rep';
    case EDIT_SALES_REP = 'edit sales rep';
    case DELETE_SALES_REP = 'delete sales rep';

    /*
     * Permission Action
     * */
    case ASSIGN_PERMISSIONS = 'assign permissions';

    /*
    * Unit Permission
    * */
    case ADD_UNIT = 'add unit';
    case VIEW_UNIT = 'view unit';
    case EDIT_UNIT = 'edit unit';
    case DELETE_UNIT = 'delete unit';
}
