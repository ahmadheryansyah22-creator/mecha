<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RatingController extends Controller
{
    public function index()
    {
        try {
            $ratings = Rating::with(['order', 'mechanic'])->paginate(10);
            return response()->json([
                'success' => true,
                'message' => 'Data ratings retrieved successfully',
                'data' => $ratings
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
                'order_id' => 'required|exists:orders,id',
                'mechanic_id' => 'required|exists:mechanics,id',
                'service_quality' => 'required|integer|min:1|max:5',
                'professionalism' => 'required|integer|min:1|max:5',
                'timeliness' => 'required|integer|min:1|max:5',
                'overall_rating' => 'required|integer|min:1|max:5',
                'review' => 'nullable|string',
                'would_recommend' => 'required|boolean',
                'tanggal_rating' => 'required|date',
            ]);

            $rating = Rating::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Rating created successfully',
                'data' => $rating
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Rating $rating)
    {
        try {
            $rating->load(['order', 'mechanic']);
            return response()->json([
                'success' => true,
                'message' => 'Rating retrieved successfully',
                'data' => $rating
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, Rating $rating)
    {
        try {
            $validated = $request->validate([
                'order_id' => 'sometimes|exists:orders,id',
                'mechanic_id' => 'sometimes|exists:mechanics,id',
                'service_quality' => 'sometimes|integer|min:1|max:5',
                'professionalism' => 'sometimes|integer|min:1|max:5',
                'timeliness' => 'sometimes|integer|min:1|max:5',
                'overall_rating' => 'sometimes|integer|min:1|max:5',
                'review' => 'nullable|string',
                'would_recommend' => 'sometimes|boolean',
                'tanggal_rating' => 'sometimes|date',
            ]);

            $rating->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Rating updated successfully',
                'data' => $rating
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Rating $rating)
    {
        try {
            $rating->delete();
            return response()->json([
                'success' => true,
                'message' => 'Rating deleted successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}