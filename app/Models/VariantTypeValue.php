<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $variant_type_id
 * @property string $name
 * @property string $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class VariantTypeValue extends Model
{
    /** @use HasFactory<\Database\Factories\VariantTypeValueFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'variant_type_id',
        'name',
        'slug',
    ];

    #[\Override]
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($variantValue): void {
            $variantType = $variantValue->variantType()->first();
            if ($variantType) {
                $combined = $variantType->name.' '.$variantValue->name;
                $variantValue->slug = Str::slug($combined);
            } else {
                $variantValue->slug = Str::slug($variantValue->name);
            }
        });
    }

    public function variantType(): BelongsTo
    {
        return $this->belongsTo(VariantType::class);
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => ucwords($value),
        );
    }
}
