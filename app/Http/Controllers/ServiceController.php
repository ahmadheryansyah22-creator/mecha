<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ServiceController extends Controller
{
    public function index()
    {
        try {
            $services = Service::with('orderItems')->paginate(10);
            return response()->json([
                'success' => true,
                'message' => 'Data services retrieved successfully',
                'data' => $services
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
                'name' => 'required|string|unique:services',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'duration_minutes' => 'nullable|integer',
                'service_type' => 'required|string',
                'requirements' => 'nullable|string',
                'status' => 'required|in:aktif,nonaktif',
            ]);

            $service = Service::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Service created successfully',
                'data' => $service
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Service $service)
    {
        try {
            $service->load('orderItems');
            return response()->json([
                'success' => true,
                'message' => 'Service retrieved successfully',
                'data' => $service
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, Service $service)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|unique:services,name,' . $service->id,
                'description' => 'nullable|string',
                'price' => 'sometimes|numeric',
                'duration_minutes' => 'nullable|integer',
                'service_type' => 'sometimes|string',
                'requirements' => 'nullable|string',
                'status' => 'sometimes|in:aktif,nonaktif',
            ]);

            $service->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Service updated successfully',
                'data' => $service
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Service $service)
    {
        try {
            $service->delete();
            return response()->json([
                'success' => true,
                'message' => 'Service deleted successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}