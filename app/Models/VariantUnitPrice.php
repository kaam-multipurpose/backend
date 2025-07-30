<?php

namespace App\Models;

use Database\Factories\VariantUnitPriceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariantUnitPrice extends Model
{
    /** @use HasFactory<VariantUnitPriceFactory> */
    use HasFactory;

    protected $fillable = [
        "variant_id",
        "product_unit_id",
        "selling_price",
    ];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    public function productUnit(): BelongsTo
    {
        return $this->belongsTo(ProductUnit::class);
    }
}
