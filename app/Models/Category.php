<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Override;

/**
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property string $slug
 * @property Attribute $allVariantTypes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
    ];

    public function subCategories(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function variantTypes(): BelongsToMany
    {
        return $this->belongsToMany(VariantType::class, 'category_variant_types');
    }

    #[Override]
    protected static function boot(): void
    {
        parent::boot();

        self::creating(function ($category): void {
            $parentCategory = $category->category()->first();
            if ($parentCategory) {
                $combined = $parentCategory->name.' '.$category->name;
                $category->slug = Str::slug($combined);
            } else {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    #[Scope]
    protected function categories(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn (string $value): string => ucwords($value),
        );
    }

    protected function allVariantTypes(): Attribute
    {
        return Attribute::make(
            get: function () {
                $own = $this->variantTypes;
                $parent = $this->category;

                $inherited = $parent ? $parent->variantTypes : collect();

                return $own->merge($inherited)->unique('id');
            }
        );
    }
}
