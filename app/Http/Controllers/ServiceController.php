<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class ServiceController extends Controller
{
    #[OA\Get(path: "/api/services", tags: ["Service"], summary: "Get semua services", security: [["token" => []]], responses: [new OA\Response(response: 200, description: "Data services retrieved successfully")])]
    public function index()
    {
        try {
            $services = Service::with("orderItems")->paginate(10);
            return response()->json(["success" => true, "message" => "Data services retrieved successfully", "data" => $services], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Post(path: "/api/services", tags: ["Service"], summary: "Tambah service baru", security: [["token" => []]], responses: [new OA\Response(response: 201, description: "Service created successfully")])]
    public function store(Request $request)
    {
        try {
            $validated = $request->validate(["name" => "required|string|unique:services", "description" => "nullable|string", "price" => "required|numeric", "duration_minutes" => "nullable|integer", "service_type" => "required|string", "requirements" => "nullable|string", "status" => "required|in:aktif,nonaktif"]);
            $service = Service::create($validated);
            return response()->json(["success" => true, "message" => "Service created successfully", "data" => $service], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Get(path: "/api/services/{id}", tags: ["Service"], summary: "Get service by ID", security: [["token" => []]], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))], responses: [new OA\Response(response: 200, description: "Service retrieved successfully")])]
    public function show(Service $service)
    {
        try {
            $service->load("orderItems");
            return response()->json(["success" => true, "message" => "Service retrieved successfully", "data" => $service], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Put(path: "/api/services/{id}", tags: ["Service"], summary: "Update service", security: [["token" => []]], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))], responses: [new OA\Response(response: 200, description: "Service updated successfully")])]
    public function update(Request $request, Service $service)
    {
        try {
            $validated = $request->validate(["name" => "sometimes|string|unique:services,name," . $service->id, "price" => "sometimes|numeric", "status" => "sometimes|in:aktif,nonaktif"]);
            $service->update($validated);
            return response()->json(["success" => true, "message" => "Service updated successfully", "data" => $service], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Delete(path: "/api/services/{id}", tags: ["Service"], summary: "Hapus service", security: [["token" => []]], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))], responses: [new OA\Response(response: 200, description: "Service deleted successfully")])]
    public function destroy(Service $service)
    {
        try {
            $service->delete();
            return response()->json(["success" => true, "message" => "Service deleted successfully"], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}