<?php

namespace App\Http\Controllers\Unit;

use App\Dtos\AddUnitDto;
use App\Dtos\GetPaginatedUnitsDto;
use App\Dtos\UpdateUnitDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddUnitRequest;
use App\Http\Requests\GetUnitsRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use App\Services\Contracts\UnitServiceContract;
use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UnitController extends Controller
{
    public function __construct(
        private readonly UnitServiceContract $unitService,
    ) {
    }

    public function addUnit(AddUnitRequest $request): JsonResponse
    {
        $unit = $this->unitService->addUnit(
            AddUnitDto::fromValidated($request->validated()),
        );
        self::logInfo("Unit Added Successfully");
        return ApiResponse::success(
            data: new UnitResource($unit),
            message: "Unit Added Successfully",
            status: Response::HTTP_CREATED
        );
    }

    public function updateUnit(UpdateUnitRequest $request, Unit $unit): JsonResponse
    {
        $unit = $this->unitService->updateUnit(
            UpdateUnitDto::fromValidated($request->validated()),
            $unit
        );
        self::logInfo("Unit Updated Successfully");
        return ApiResponse::success(
            data: new UnitResource($unit),
            message: "Unit Updated Successfully",
        );
    }

    public function getUnits(GetUnitsRequest $request): JsonResponse
    {
        $units = $this->unitService->getUnits(
            GetPaginatedUnitsDto::fromValidated($request->validated()),
        );
        self::logInfo("Unit Listed Successfully");
        return ApiResponse::success(
            data: UnitResource::collection($units),
            message: "Unit Listed Successfully",
        );
    }

    public function deleteUnit(Unit $unit): JsonResponse
    {
        $this->unitService->deleteUnit($unit);
        self::logInfo("Unit delete Successfully");
        return ApiResponse::success(
            status: Response::HTTP_NO_CONTENT,
        );
    }
}
