<?php

namespace App\Services;

use App\Dto\CreateVariantDto;
use App\Exceptions\VariantServiceException;
use App\Models\Product;
use App\Models\Variant;
use App\Services\Contracts\VariantServiceContract;
use App\Utils\Logger\Contract\LoggerContract;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VariantService implements Contracts\VariantServiceContract
{
    use HasAuthenticatedUser, HasLogger;

    public function __construct(
        protected LoggerContract $logger,
    )
    {

    }

    /**
     * @inheritDoc
     * @throws VariantServiceException
     */
    public function createVariant(CreateVariantDto $createVariantDto, Product $product): Variant
    {
        try {
            $this->log("info", "Creating variant", [
                "product_id" => $product->id,
            ]);
            return $product->variants()->create($createVariantDto->toArray());
        } catch (\Throwable $e) {

            $this->log("info", "Failed Creating variant", [
                "product_id" => $product->id,
                "payload" => $createVariantDto->toArray(),
            ]);
            throw new VariantServiceException("Unable to create variant for product", $e->getCode(), $e);
        }
    }

    /**
     * @inheritDoc
     * @throws VariantServiceException
     */
    public function createManyVariant(array $variantsDto, Product $product): bool
    {
        try {
            DB::beginTransaction();

            $this->log("info", "Creating Bulk variant", [
                "product_id" => $product->id,
            ]);

            $variants = array_map(function (CreateVariantDto $variant) use ($product) {

                $nameSlug = Str::slug("{$product->name}-{$variant->name}") . "-" . now()->timestamp . "-" . Str::random(3);

                return array_merge($variant->toArray(), [
                    "product_id" => $product->id,
                    "slug" => $nameSlug,
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
            }, $variantsDto);

            $createdVariants = Variant::insert($variants);

            DB::commit();
            return $createdVariants;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->log("info", "failed Creating Bulk variant", [
                "product_id" => $product->id,
            ]);
            throw new VariantServiceException("Unable to create variant for product", $e->getCode(), $e);
        }
    }
}
