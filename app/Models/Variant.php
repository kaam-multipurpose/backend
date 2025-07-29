<?php

namespace App\Models;

use Database\Factories\VariantsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Variant extends Model
{
    /** @use HasFactory<VariantsFactory> */
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
}
