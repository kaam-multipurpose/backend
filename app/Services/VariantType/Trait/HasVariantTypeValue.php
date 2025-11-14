<?php

declare(strict_types=1);

namespace App\Services\VariantType\Trait;

use App\Exceptions\VariantTypeServiceException;
use App\Models\VariantType;
use App\Models\VariantTypeValue;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

trait HasVariantTypeValue
{
    /**
     * @throws VariantTypeServiceException
     */
    public function deleteVariantTypeValue(VariantType $variantType, VariantTypeValue $variantTypeValue): void
    {
        try {
            self::logInfo('Attempt to delete variant type', [
                'variantTypeId' => $variantType->id,
                'variantTypeValueId' => $variantTypeValue->id,
            ]);

            $variantTypeValues = $variantType->variantTypeValues()->pluck('name')->toArray();

            if (! in_array($variantTypeValue->name, $variantTypeValues)) {
                throw new VariantTypeServiceException('variant value mismatch', Response::HTTP_FORBIDDEN);
            }

            $variantTypeValue->forceDelete();

        } catch (Throwable $throwable) {
            self::logException($throwable, 'Caught Exception when deleting variant type value', [
                'variantTypeId' => $variantType->id,
                'variantTypeValueId' => $variantTypeValue->id,
            ]);
            if ($throwable instanceof VariantTypeServiceException) {
                throw $throwable;
            }

            throw new VariantTypeServiceException('Unable to delete variant type');
        }
    }
}
