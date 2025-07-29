<?php

namespace App\Models;

use App\Enum\ProductVariantsTypeEnum;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        "category_id",
        "variant_type"
    ];

    protected $casts = [
        "variant_type" => ProductVariantsTypeEnum::class,
    ];

    protected static function booted(): void
    {
        static::creating(function ($product) {
            $product->slug = Str::slug("{$product->name}-" . now()->timestamp . '-' . Str::random(3));
        });
    }

    public function getRouteKeyName(): string
    {
        return "slug";
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(ProductUnit::class);
    }
}
