<?php

namespace App\Services\VariantType\Trait;

use App\Exceptions\VariantTypeServiceException;
use App\Models\VariantType;
use App\Models\VariantTypeValue;
use Symfony\Component\HttpFoundation\Response;

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

        } catch (\Throwable $e) {
            self::logException($e, 'Caught Exception when deleting variant type value', [
                'variantTypeId' => $variantType->id,
                'variantTypeValueId' => $variantTypeValue->id,
            ]);
            if ($e instanceof VariantTypeServiceException) {
                throw $e;
            }
            throw new VariantTypeServiceException('Unable to delete variant type');
        }
    }
}
