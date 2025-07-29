<?php

namespace App\Models;

use App\Enum\ProductUnitsEnum;
use Database\Factories\ProductUnitFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductUnit extends Model
{
    /** @use HasFactory<ProductUnitFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        "multiplier",
        "conversion_rate",
        "is_base",
        "is_max"
    ];

    protected $casts = [
        "is_base" => "boolean",
        "is_max" => "boolean",
        "multiplier" => "float",
        "name" => ProductUnitsEnum::class,
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
