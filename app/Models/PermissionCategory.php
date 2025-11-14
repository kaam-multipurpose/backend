<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class PermissionCategory extends Model
{
    protected $fillable = [
        'name',
    ];

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }
}
