<?php

namespace App\Http\Controllers;

use App\Dto\createUnitDto;
use App\Exceptions\UnitServiceException;
use App\Http\Resources\UnitResource;
use App\Services\Contracts\UnitServiceContract;
use App\Utils\Logger\Contract\LoggerContract;
use App\Utils\Response\ApiResponse;
use App\Utils\Trait\HasAuthenticatedUser;
use App\Utils\Trait\HasLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    use HasAuthenticatedUser, HasLogger;

    public function __construct(
        protected UnitServiceContract $unitService,
        protected LoggerContract      $logger
    )
    {
    }

    /**
     * @throws UnitServiceException
     */
    public function createUnit(Request $request): JsonResponse
    {
        $validated = $request->validate([
            "name" => ["required", "string"],
        ]);
        $response = $this->unitService->createUnit(CreateUnitDto::fromValidated($validated));

        return ApiResponse::success(
            new UnitResource($response),
            "Successfully created unit",
            201
        );
    }
}
