<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class VehicleController extends Controller
{
    #[OA\Get(path: "/api/vehicles", tags: ["Vehicle"], summary: "Get semua vehicles", security: [["token" => []]], responses: [new OA\Response(response: 200, description: "Data vehicles retrieved successfully")])]
    public function index()
    {
        try {
            $vehicles = Vehicle::with(["bengkel", "diagnostics", "orders"])->paginate(10);
            return response()->json(["success" => true, "message" => "Data vehicles retrieved successfully", "data" => $vehicles], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Post(path: "/api/vehicles", tags: ["Vehicle"], summary: "Tambah vehicle baru", security: [["token" => []]], responses: [new OA\Response(response: 201, description: "Vehicle created successfully")])]
    public function store(Request $request)
    {
        try {
            $validated = $request->validate(["bengkel_id" => "required|exists:bengkels,id", "license_plate" => "required|string|unique:vehicles", "owner_name" => "required|string", "owner_phone" => "required|string", "vehicle_type" => "required|string", "brand" => "required|string", "model" => "required|string", "year" => "required|integer", "status" => "required|in:aktif,inactive"]);
            $vehicle = Vehicle::create($validated);
            return response()->json(["success" => true, "message" => "Vehicle created successfully", "data" => $vehicle], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Get(path: "/api/vehicles/{id}", tags: ["Vehicle"], summary: "Get vehicle by ID", security: [["token" => []]], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))], responses: [new OA\Response(response: 200, description: "Vehicle retrieved successfully")])]
    public function show(Vehicle $vehicle)
    {
        try {
            $vehicle->load(["bengkel", "diagnostics", "orders"]);
            return response()->json(["success" => true, "message" => "Vehicle retrieved successfully", "data" => $vehicle], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Put(path: "/api/vehicles/{id}", tags: ["Vehicle"], summary: "Update vehicle", security: [["token" => []]], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))], responses: [new OA\Response(response: 200, description: "Vehicle updated successfully")])]
    public function update(Request $request, Vehicle $vehicle)
    {
        try {
            $validated = $request->validate(["license_plate" => "sometimes|string|unique:vehicles,license_plate," . $vehicle->id, "owner_name" => "sometimes|string", "brand" => "sometimes|string", "model" => "sometimes|string", "status" => "sometimes|in:aktif,inactive"]);
            $vehicle->update($validated);
            return response()->json(["success" => true, "message" => "Vehicle updated successfully", "data" => $vehicle], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Delete(path: "/api/vehicles/{id}", tags: ["Vehicle"], summary: "Hapus vehicle", security: [["token" => []]], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))], responses: [new OA\Response(response: 200, description: "Vehicle deleted successfully")])]
    public function destroy(Vehicle $vehicle)
    {
        try {
            $vehicle->delete();
            return response()->json(["success" => true, "message" => "Vehicle deleted successfully"], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}