<?php

namespace App\Http\Controllers;

use App\Models\Bengkel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class BengkelController extends Controller
{
    #[OA\Get(path: "/api/bengkels", tags: ["Bengkel"], summary: "Get semua bengkel",
        security: [["token" => []]],
        responses: [new OA\Response(response: 200, description: "Data bengkels retrieved successfully")]
    )]
    public function index()
    {
        try {
            $bengkels = Bengkel::with(["mechanics", "vehicles", "orders"])->paginate(10);
            return response()->json(["success" => true, "message" => "Data bengkels retrieved successfully", "data" => $bengkels], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Post(path: "/api/bengkels", tags: ["Bengkel"], summary: "Tambah bengkel baru",
        security: [["token" => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ["name","address","phone","email","city","province","status","owner_name"],
            properties: [
                new OA\Property(property: "name", type: "string"),
                new OA\Property(property: "address", type: "string"),
                new OA\Property(property: "phone", type: "string"),
                new OA\Property(property: "email", type: "string", format: "email"),
                new OA\Property(property: "city", type: "string"),
                new OA\Property(property: "province", type: "string"),
                new OA\Property(property: "status", type: "string", enum: ["aktif","nonaktif"]),
                new OA\Property(property: "owner_name", type: "string"),
            ]
        )),
        responses: [new OA\Response(response: 201, description: "Bengkel created successfully")]
    )]
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                "name" => "required|string|unique:bengkels",
                "address" => "required|string",
                "phone" => "required|string",
                "email" => "required|email|unique:bengkels",
                "city" => "required|string",
                "province" => "required|string",
                "latitude" => "nullable|numeric",
                "longitude" => "nullable|numeric",
                "description" => "nullable|string",
                "status" => "required|in:aktif,nonaktif",
                "owner_name" => "required|string",
                "owner_phone" => "nullable|string",
            ]);
            $bengkel = Bengkel::create($validated);
            return response()->json(["success" => true, "message" => "Bengkel created successfully", "data" => $bengkel], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Get(path: "/api/bengkels/{id}", tags: ["Bengkel"], summary: "Get bengkel by ID",
        security: [["token" => []]],
        parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        responses: [new OA\Response(response: 200, description: "Bengkel retrieved successfully")]
    )]
    public function show(Bengkel $bengkel)
    {
        try {
            $bengkel->load(["mechanics", "vehicles", "orders"]);
            return response()->json(["success" => true, "message" => "Bengkel retrieved successfully", "data" => $bengkel], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Put(path: "/api/bengkels/{id}", tags: ["Bengkel"], summary: "Update bengkel",
        security: [["token" => []]],
        parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        responses: [new OA\Response(response: 200, description: "Bengkel updated successfully")]
    )]
    public function update(Request $request, Bengkel $bengkel)
    {
        try {
            $validated = $request->validate([
                "name" => "sometimes|string|unique:bengkels,name," . $bengkel->id,
                "address" => "sometimes|string",
                "phone" => "sometimes|string",
                "email" => "sometimes|email|unique:bengkels,email," . $bengkel->id,
                "city" => "sometimes|string",
                "province" => "sometimes|string",
                "status" => "sometimes|in:aktif,nonaktif",
                "owner_name" => "sometimes|string",
            ]);
            $bengkel->update($validated);
            return response()->json(["success" => true, "message" => "Bengkel updated successfully", "data" => $bengkel], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Delete(path: "/api/bengkels/{id}", tags: ["Bengkel"], summary: "Hapus bengkel",
        security: [["token" => []]],
        parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        responses: [new OA\Response(response: 200, description: "Bengkel deleted successfully")]
    )]
    public function destroy(Bengkel $bengkel)
    {
        try {
            $bengkel->delete();
            return response()->json(["success" => true, "message" => "Bengkel deleted successfully"], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}