<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $guard_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'slug',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($role) {
            $role->slug = Str::slug($role->name);
        });
    }
}
