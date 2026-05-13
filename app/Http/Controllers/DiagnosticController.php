<?php

namespace App\Http\Controllers;

use App\Models\Diagnostic;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DiagnosticController extends Controller
{
    public function index()
    {
        try {
            $diagnostics = Diagnostic::with(['vehicle', 'mechanic', 'aiDiagnostic'])->paginate(10);
            return response()->json([
                'success' => true,
                'message' => 'Data diagnostics retrieved successfully',
                'data' => $diagnostics
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
                'vehicle_id' => 'required|exists:vehicles,id',
                'mechanic_id' => 'nullable|exists:mechanics,id',
                'customer_complaint' => 'required|string',
                'visual_inspection' => 'nullable|string',
                'findings' => 'nullable|string',
                'affected_systems' => 'nullable|array',
                'estimated_cost' => 'nullable|numeric',
                'severity' => 'nullable|in:ringan,sedang,berat',
                'status' => 'required|in:pending,in_progress,completed',
                'completed_at' => 'nullable|date',
            ]);

            $diagnostic = Diagnostic::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Diagnostic created successfully',
                'data' => $diagnostic
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Diagnostic $diagnostic)
    {
        try {
            $diagnostic->load(['vehicle', 'mechanic', 'aiDiagnostic']);
            return response()->json([
                'success' => true,
                'message' => 'Diagnostic retrieved successfully',
                'data' => $diagnostic
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, Diagnostic $diagnostic)
    {
        try {
            $validated = $request->validate([
                'vehicle_id' => 'sometimes|exists:vehicles,id',
                'mechanic_id' => 'nullable|exists:mechanics,id',
                'customer_complaint' => 'sometimes|string',
                'visual_inspection' => 'nullable|string',
                'findings' => 'nullable|string',
                'affected_systems' => 'nullable|array',
                'estimated_cost' => 'nullable|numeric',
                'severity' => 'nullable|in:ringan,sedang,berat',
                'status' => 'sometimes|in:pending,in_progress,completed',
                'completed_at' => 'nullable|date',
            ]);

            $diagnostic->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Diagnostic updated successfully',
                'data' => $diagnostic
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Diagnostic $diagnostic)
    {
        try {
            $diagnostic->delete();
            return response()->json([
                'success' => true,
                'message' => 'Diagnostic deleted successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}