<?php

declare(strict_types=1);

namespace App\Http\Controllers\VariantType;

use App\Dto\AddValuesToVariantTypeDto;
use App\Dto\AddVariantTypeDto;
use App\Dto\GetPaginatedVariantTypesDto;
use App\Exceptions\VariantTypeServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddValuesToVariantTypeRequest;
use App\Http\Requests\AddVariantTypeRequest;
use App\Http\Requests\GetVariantTypesRequest;
use App\Http\Resources\VariantTypeResource;
use App\Http\Resources\VariantTypeValueResource;
use App\Models\VariantType;
use App\Models\VariantTypeValue;
use App\Services\Contracts\VariantTypeServiceContract;
use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class VariantTypeController extends Controller
{
    use HasAuthenticatedUser;
    use HasLogger;

    public function __construct(
        private readonly VariantTypeServiceContract $variantTypeService
    ) {}

    /**
     * @throws VariantTypeServiceException
     */
    public function addVariantType(AddVariantTypeRequest $request): JsonResponse
    {
        $variantType = $this->variantTypeService->addVariantType(
            AddVariantTypeDto::fromValidated($request->validated()),
        );

        self::logInfo('Variant Type added successfully');

        return ApiResponse::success(
            new VariantTypeResource($variantType),
            'Variant Type added successfully',
            Response::HTTP_CREATED
        );
    }

    /**
     * @throws VariantTypeServiceException
     */
    public function getVariantTypes(GetVariantTypesRequest $request): JsonResponse
    {
        $variantTypesPaginator = $this->variantTypeService->getVariantTypes(
            GetPaginatedVariantTypesDto::fromValidated($request->validated()),
        );

        self::logInfo('Variant Types retrieved successfully');

        return ApiResponse::success(
            VariantTypeResource::collection($variantTypesPaginator),
            'Variant Types retrieved successfully'
        );
    }

    /**
     * @throws VariantTypeServiceException
     */
    public function addValuesToVariantType(AddValuesToVariantTypeRequest $request, VariantType $variantType): JsonResponse
    {
        $addedValues = $this->variantTypeService->addValuesToVariantType(
            AddValuesToVariantTypeDto::fromValidated($request->validated(), $variantType),
        );

        self::logInfo('Values added to Variant Type added successfully');

        return ApiResponse::success(
            VariantTypeValueResource::collection($addedValues),
            'Values added successfully',
            Response::HTTP_CREATED
        );
    }

    /**
     * @throws VariantTypeServiceException
     */
    public function deleteVariantType(Request $request, VariantType $variantType): JsonResponse
    {
        $this->variantTypeService->deleteVariantType($variantType);

        self::logInfo('Variant Type deleted successfully', [
            'variantTypeId' => $variantType->id,
        ]);

        return ApiResponse::success(
            status: Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @throws VariantTypeServiceException
     */
    public function deleteVariantTypeValue(Request $request, VariantType $variantType, VariantTypeValue $variantTypeValue): JsonResponse
    {
        $this->variantTypeService->deleteVariantTypeValue($variantType, $variantTypeValue);

        self::logInfo('Variant Type Value deleted successfully', [
            'variantTypeId' => $variantType->id,
            'variantTypeValueId' => $variantTypeValue->id,
        ]);

        return ApiResponse::success(
            status: Response::HTTP_NO_CONTENT
        );
    }
}
