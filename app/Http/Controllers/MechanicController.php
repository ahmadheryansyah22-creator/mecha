<?php

namespace App\Http\Controllers;

use App\Models\Mechanic;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class MechanicController extends Controller
{
    #[OA\Get(path: "/api/mechanics", tags: ["Mechanic"], summary: "Get semua mechanics", security: [["token" => []]], responses: [new OA\Response(response: 200, description: "Data mechanics retrieved successfully")])]
    public function index()
    {
        try {
            $mechanics = Mechanic::with(["bengkel", "diagnostics", "orders", "ratings"])->paginate(10);
            return response()->json(["success" => true, "message" => "Data mechanics retrieved successfully", "data" => $mechanics], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Post(path: "/api/mechanics", tags: ["Mechanic"], summary: "Tambah mechanic baru", security: [["token" => []]], responses: [new OA\Response(response: 201, description: "Mechanic created successfully")])]
    public function store(Request $request)
    {
        try {
            $validated = $request->validate(["bengkel_id" => "required|exists:bengkels,id", "name" => "required|string", "phone" => "required|string", "email" => "required|email|unique:mechanics", "expertise" => "nullable|string", "salary" => "nullable|numeric", "experience_years" => "nullable|integer", "certification" => "nullable|string", "status" => "required|in:aktif,cuti,resigned", "notes" => "nullable|string", "join_date" => "nullable|date"]);
            $mechanic = Mechanic::create($validated);
            return response()->json(["success" => true, "message" => "Mechanic created successfully", "data" => $mechanic], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Get(path: "/api/mechanics/{id}", tags: ["Mechanic"], summary: "Get mechanic by ID", security: [["token" => []]], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))], responses: [new OA\Response(response: 200, description: "Mechanic retrieved successfully")])]
    public function show(Mechanic $mechanic)
    {
        try {
            $mechanic->load(["bengkel", "diagnostics", "orders", "ratings"]);
            return response()->json(["success" => true, "message" => "Mechanic retrieved successfully", "data" => $mechanic], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Put(path: "/api/mechanics/{id}", tags: ["Mechanic"], summary: "Update mechanic", security: [["token" => []]], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))], responses: [new OA\Response(response: 200, description: "Mechanic updated successfully")])]
    public function update(Request $request, Mechanic $mechanic)
    {
        try {
            $validated = $request->validate(["bengkel_id" => "sometimes|exists:bengkels,id", "name" => "sometimes|string", "phone" => "sometimes|string", "email" => "sometimes|email|unique:mechanics,email," . $mechanic->id, "status" => "sometimes|in:aktif,cuti,resigned"]);
            $mechanic->update($validated);
            return response()->json(["success" => true, "message" => "Mechanic updated successfully", "data" => $mechanic], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Delete(path: "/api/mechanics/{id}", tags: ["Mechanic"], summary: "Hapus mechanic", security: [["token" => []]], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))], responses: [new OA\Response(response: 200, description: "Mechanic deleted successfully")])]
    public function destroy(Mechanic $mechanic)
    {
        try {
            $mechanic->delete();
            return response()->json(["success" => true, "message" => "Mechanic deleted successfully"], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}