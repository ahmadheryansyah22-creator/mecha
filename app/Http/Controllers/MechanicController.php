<?php

namespace App\Http\Controllers;

use App\Models\Mechanic;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MechanicController extends Controller
{
    public function index()
    {
        try {
            $mechanics = Mechanic::with(['bengkel', 'diagnostics', 'orders', 'ratings'])->paginate(10);
            return response()->json([
                'success' => true,
                'message' => 'Data mechanics retrieved successfully',
                'data' => $mechanics
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
                'name' => 'required|string',
                'phone' => 'required|string',
                'email' => 'required|email|unique:mechanics',
                'expertise' => 'nullable|string',
                'salary' => 'nullable|numeric',
                'experience_years' => 'nullable|integer',
                'certification' => 'nullable|string',
                'status' => 'required|in:aktif,cuti,resigned',
                'notes' => 'nullable|string',
                'join_date' => 'nullable|date',
            ]);

            $mechanic = Mechanic::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Mechanic created successfully',
                'data' => $mechanic
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Mechanic $mechanic)
    {
        try {
            $mechanic->load(['bengkel', 'diagnostics', 'orders', 'ratings']);
            return response()->json([
                'success' => true,
                'message' => 'Mechanic retrieved successfully',
                'data' => $mechanic
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, Mechanic $mechanic)
    {
        try {
            $validated = $request->validate([
                'bengkel_id' => 'sometimes|exists:bengkels,id',
                'name' => 'sometimes|string',
                'phone' => 'sometimes|string',
                'email' => 'sometimes|email|unique:mechanics,email,' . $mechanic->id,
                'expertise' => 'nullable|string',
                'salary' => 'nullable|numeric',
                'experience_years' => 'nullable|integer',
                'certification' => 'nullable|string',
                'status' => 'sometimes|in:aktif,cuti,resigned',
                'notes' => 'nullable|string',
                'join_date' => 'nullable|date',
            ]);

            $mechanic->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Mechanic updated successfully',
                'data' => $mechanic
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Mechanic $mechanic)
    {
        try {
            $mechanic->delete();
            return response()->json([
                'success' => true,
                'message' => 'Mechanic deleted successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}