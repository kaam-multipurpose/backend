<?php

namespace App\Models;

use Database\Factories\VariantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Variant extends Model
{
    /** @use HasFactory<VariantFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        "product_id",
        'slug',
        'cost_price',
    ];

    protected static function booted(): void
    {
        static::creating(function ($variant) {
            $variant->slug = Str::slug("{$variant->product->name}-{$variant->name}-" . now()->timestamp . '-' . Str::random(3));
        });
    }

    public function getRouteKeyName(): string
    {
        return "slug";
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function unitPrices(): HasMany
    {
        return $this->hasMany(VariantUnitPrice::class);
    }
}
