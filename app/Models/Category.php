<?php

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

/**
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property string $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory, softDeletes;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
    ];

    #[\Override]
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($category): void {
            $parentCategory = $category->category()->first();
            if ($parentCategory) {
                $combined = $parentCategory->name.' '.$category->name;
                $category->slug = Str::slug($combined);
            } else {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function subCategories(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    #[Scope]
    public function categories(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function variantTypes(): BelongsToMany
    {
        return $this->belongsToMany(VariantType::class, 'category_variant_types');
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => ucwords($value),
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
