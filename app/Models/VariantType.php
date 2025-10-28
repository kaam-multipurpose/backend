<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class VariantType extends Model
{
    /** @use HasFactory<\Database\Factories\VariantTypeFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
    ];

    protected $hidden = [
        'parent_id',
    ];

    #[\Override]
    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (VariantType $variantType): void {
            $variantType->slug = Str::slug($variantType->name);
        });

        static::deleting(function (VariantType $variantType): void {
            $variantType->variantTypeValues()->delete();
        });
    }

    public function variantTypeValues(): HasMany
    {
        return $this->hasMany(VariantTypeValue::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_variant_types');
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => ucwords($value),
        );
    }
}
