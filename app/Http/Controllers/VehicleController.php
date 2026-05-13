<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VehicleController extends Controller
{
    public function index()
    {
        try {
            $vehicles = Vehicle::with(['bengkel', 'diagnostics', 'orders', 'aiDiagnostics'])->paginate(10);
            return response()->json([
                'success' => true,
                'message' => 'Data vehicles retrieved successfully',
                'data' => $vehicles
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'bengkel_id' => 'required|exists:bengkels,id',
                'license_plate' => 'required|string|unique:vehicles',
                'owner_name' => 'required|string',
                'owner_phone' => 'required|string',
                'owner_email' => 'nullable|email',
                'vehicle_type' => 'required|string',
                'brand' => 'required|string',
                'model' => 'required|string',
                'year' => 'required|integer',
                'color' => 'nullable|string',
                'vin' => 'nullable|string|unique:vehicles',
                'mileage' => 'nullable|integer',
                'notes' => 'nullable|string',
                'status' => 'required|in:aktif,inactive',
                'last_service' => 'nullable|date',
            ]);

            $vehicle = Vehicle::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle created successfully',
                'data' => $vehicle
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Vehicle $vehicle)
    {
        try {
            $vehicle->load(['bengkel', 'diagnostics', 'orders', 'aiDiagnostics']);
            return response()->json([
                'success' => true,
                'message' => 'Vehicle retrieved successfully',
                'data' => $vehicle
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        try {
            $validated = $request->validate([
                'bengkel_id' => 'sometimes|exists:bengkels,id',
                'license_plate' => 'sometimes|string|unique:vehicles,license_plate,' . $vehicle->id,
                'owner_name' => 'sometimes|string',
                'owner_phone' => 'sometimes|string',
                'owner_email' => 'nullable|email',
                'vehicle_type' => 'sometimes|string',
                'brand' => 'sometimes|string',
                'model' => 'sometimes|string',
                'year' => 'sometimes|integer',
                'color' => 'nullable|string',
                'vin' => 'nullable|string|unique:vehicles,vin,' . $vehicle->id,
                'mileage' => 'nullable|integer',
                'notes' => 'nullable|string',
                'status' => 'sometimes|in:aktif,inactive',
                'last_service' => 'nullable|date',
            ]);

            $vehicle->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle updated successfully',
                'data' => $vehicle
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Vehicle $vehicle)
    {
        try {
            $vehicle->delete();
            return response()->json([
                'success' => true,
                'message' => 'Vehicle deleted successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}