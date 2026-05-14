<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class RatingController extends Controller
{
    #[OA\Get(path: "/api/ratings", tags: ["Rating"], summary: "Get semua ratings", security: [["token" => []]], responses: [new OA\Response(response: 200, description: "Data ratings retrieved successfully")])]
    public function index()
    {
        try {
            $ratings = Rating::with(["order", "mechanic"])->paginate(10);
            return response()->json(["success" => true, "message" => "Data ratings retrieved successfully", "data" => $ratings], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Post(path: "/api/ratings", tags: ["Rating"], summary: "Tambah rating baru", security: [["token" => []]], responses: [new OA\Response(response: 201, description: "Rating created successfully")])]
    public function store(Request $request)
    {
        try {
            $validated = $request->validate(["order_id" => "required|exists:orders,id", "mechanic_id" => "required|exists:mechanics,id", "service_quality" => "required|integer|min:1|max:5", "professionalism" => "required|integer|min:1|max:5", "timeliness" => "required|integer|min:1|max:5", "overall_rating" => "required|integer|min:1|max:5", "review" => "nullable|string", "would_recommend" => "required|boolean", "tanggal_rating" => "required|date"]);
            $rating = Rating::create($validated);
            return response()->json(["success" => true, "message" => "Rating created successfully", "data" => $rating], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Get(path: "/api/ratings/{id}", tags: ["Rating"], summary: "Get rating by ID", security: [["token" => []]], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))], responses: [new OA\Response(response: 200, description: "Rating retrieved successfully")])]
    public function show(Rating $rating)
    {
        try {
            $rating->load(["order", "mechanic"]);
            return response()->json(["success" => true, "message" => "Rating retrieved successfully", "data" => $rating], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Put(path: "/api/ratings/{id}", tags: ["Rating"], summary: "Update rating", security: [["token" => []]], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))], responses: [new OA\Response(response: 200, description: "Rating updated successfully")])]
    public function update(Request $request, Rating $rating)
    {
        try {
            $validated = $request->validate(["service_quality" => "sometimes|integer|min:1|max:5", "overall_rating" => "sometimes|integer|min:1|max:5", "review" => "nullable|string"]);
            $rating->update($validated);
            return response()->json(["success" => true, "message" => "Rating updated successfully", "data" => $rating], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Delete(path: "/api/ratings/{id}", tags: ["Rating"], summary: "Hapus rating", security: [["token" => []]], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))], responses: [new OA\Response(response: 200, description: "Rating deleted successfully")])]
    public function destroy(Rating $rating)
    {
        try {
            $rating->delete();
            return response()->json(["success" => true, "message" => "Rating deleted successfully"], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}