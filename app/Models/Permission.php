<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * @property int $id
 * @property int $permission_category_id
 * @property string $name
 * @property string $guard_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'guard_name',
        'permission_category_id',
    ];
}
