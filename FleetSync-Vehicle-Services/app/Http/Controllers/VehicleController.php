<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Http\Resources\VehicleResources;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Vehicle Service API",
 *     description="API documentation for Vehicle Service",
 *     @OA\Contact(
 *         email="admin@example.com"
 *     )
 * )
 */
class VehicleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/vehicles",
     *     summary="Get all vehicles",
     *     tags={"Vehicles"},
     *     @OA\Response(
     *         response=200,
     *         description="List of vehicles",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="type", type="string", example="Car"),
     *                     @OA\Property(property="plate_number", type="string", example="B 1234 ABC"),
     *                     @OA\Property(property="status", type="string", example="Available"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $vehicles = Vehicle::all();
        return response()->json([
            'status' => 'success',
            'data' => VehicleResources::collection($vehicles)
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/vehicles",
     *     summary="Create a new vehicle",
     *     tags={"Vehicles"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"type", "plate_number", "status"},
     *             @OA\Property(property="type", type="string", example="Car"),
     *             @OA\Property(property="plate_number", type="string", example="B 1234 ABC"),
     *             @OA\Property(property="status", type="string", enum={"Available", "InUse", "Maintenance"}, example="Available")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vehicle created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Vehicle created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="type", type="string", example="Car"),
     *                 @OA\Property(property="plate_number", type="string", example="B 1234 ABC"),
     *                 @OA\Property(property="status", type="string", example="Available"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'plate_number' => 'required|string|unique:vehicles',
            'status' => 'required|in:Available,InUse,Maintenance'
        ]);

        $vehicle = Vehicle::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Vehicle created successfully',
            'data' => new VehicleResources($vehicle)
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/vehicles/{id}",
     *     summary="Get a specific vehicle",
     *     tags={"Vehicles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Vehicle ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vehicle details",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="type", type="string", example="Car"),
     *                 @OA\Property(property="plate_number", type="string", example="B 1234 ABC"),
     *                 @OA\Property(property="status", type="string", example="Available"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vehicle not found"
     *     )
     * )
     */
    public function show(Vehicle $vehicle): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => new VehicleResources($vehicle)
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/vehicles/{id}",
     *     summary="Update a vehicle",
     *     tags={"Vehicles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Vehicle ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="type", type="string", example="Car"),
     *             @OA\Property(property="plate_number", type="string", example="B 1234 ABC"),
     *             @OA\Property(property="status", type="string", enum={"Available", "InUse", "Maintenance"}, example="Available")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vehicle updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Vehicle updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="type", type="string", example="Car"),
     *                 @OA\Property(property="plate_number", type="string", example="B 1234 ABC"),
     *                 @OA\Property(property="status", type="string", example="Available"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vehicle not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(Request $request, Vehicle $vehicle): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'sometimes|required|string',
            'plate_number' => 'sometimes|required|string|unique:vehicles,plate_number,' . $vehicle->id,
            'status' => 'sometimes|required|in:Available,InUse,Maintenance'
        ]);

        $vehicle->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Vehicle updated successfully',
            'data' => new VehicleResources($vehicle)
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/vehicles/{id}",
     *     summary="Delete a vehicle",
     *     tags={"Vehicles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Vehicle ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vehicle deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Vehicle deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vehicle not found"
     *     )
     * )
     */
    public function destroy(Vehicle $vehicle): JsonResponse
    {
        $vehicle->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Vehicle deleted successfully'
        ]);
    }
}
