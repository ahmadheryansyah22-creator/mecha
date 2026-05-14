<?php

namespace App\Http\Controllers;

use App\Models\SparePart;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SparePartController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = SparePart::with('bengkel');
            
            if ($request->bengkel_id) {
                $query->where('bengkel_id', $request->bengkel_id);
            }
            if ($request->status) {
                $query->where('status', $request->status);
            }
            if ($request->low_stock) {
                $query->whereColumn('stock', '<=', 'min_stock');
            }

            $spareParts = $query->paginate(10);
            
            return response()->json([
                'success' => true,
                'message' => 'Data spare parts retrieved successfully',
                'data' => $spareParts
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'bengkel_id' => 'required|exists:bengkels,id',
                'name' => 'required|string',
                'code' => 'nullable|string',
                'category' => 'nullable|string',
                'brand' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'min_stock' => 'nullable|integer|min:0',
                'unit' => 'nullable|string',
                'status' => 'nullable|in:tersedia,habis,discontinue',
                'description' => 'nullable|string',
            ]);

            $sparePart = SparePart::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Spare part created successfully',
                'data' => $sparePart
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show(SparePart $sparePart)
    {
        try {
            $sparePart->load('bengkel');
            return response()->json([
                'success' => true,
                'message' => 'Spare part retrieved successfully',
                'data' => $sparePart
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, SparePart $sparePart)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string',
                'price' => 'sometimes|numeric|min:0',
                'stock' => 'sometimes|integer|min:0',
                'min_stock' => 'sometimes|integer|min:0',
                'status' => 'sometimes|in:tersedia,habis,discontinue',
            ]);

            $sparePart->update($validated);

            // Auto update status berdasarkan stok
            if ($sparePart->stock == 0) {
                $sparePart->update(['status' => 'habis']);
            } elseif ($sparePart->stock > 0 && $sparePart->status == 'habis') {
                $sparePart->update(['status' => 'tersedia']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Spare part updated successfully',
                'data' => $sparePart
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
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
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}