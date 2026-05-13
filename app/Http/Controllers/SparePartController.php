<?php

namespace App\Http\Controllers;

use App\Models\SparePart;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SparePartController extends Controller
{
    public function index()
    {
        try {
            $spareParts = SparePart::with('orderItems')->paginate(10);
            return response()->json([
                'success' => true,
                'message' => 'Data spare parts retrieved successfully',
                'data' => $spareParts
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
                'name' => 'required|string',
                'code' => 'required|string|unique:spare_parts',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'min_stock' => 'required|integer',
                'category' => 'required|string',
                'manufacturer' => 'nullable|string',
                'supplier' => 'nullable|string',
                'status' => 'required|in:aktif,discontinued',
                'last_restock' => 'nullable|date',
            ]);

            $sparePart = SparePart::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Spare part created successfully',
                'data' => $sparePart
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(SparePart $sparePart)
    {
        try {
            $sparePart->load('orderItems');
            return response()->json([
                'success' => true,
                'message' => 'Spare part retrieved successfully',
                'data' => $sparePart
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, SparePart $sparePart)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string',
                'code' => 'sometimes|string|unique:spare_parts,code,' . $sparePart->id,
                'description' => 'nullable|string',
                'price' => 'sometimes|numeric',
                'stock' => 'sometimes|integer',
                'min_stock' => 'sometimes|integer',
                'category' => 'sometimes|string',
                'manufacturer' => 'nullable|string',
                'supplier' => 'nullable|string',
                'status' => 'sometimes|in:aktif,discontinued',
                'last_restock' => 'nullable|date',
            ]);

            $sparePart->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Spare part updated successfully',
                'data' => $sparePart
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(SparePart $sparePart)
    {
        try {
            $sparePart->delete();
            return response()->json([
                'success' => true,
                'message' => 'Spare part deleted successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}