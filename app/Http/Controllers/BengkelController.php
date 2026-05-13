<?php

namespace App\Http\Controllers;

use App\Models\Bengkel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BengkelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $bengkels = Bengkel::with(['mechanics', 'vehicles', 'orders'])->paginate(10);
            return response()->json([
                'success' => true,
                'message' => 'Data bengkels retrieved successfully',
                'data' => $bengkels
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|unique:bengkels',
                'address' => 'required|string',
                'phone' => 'required|string',
                'email' => 'required|email|unique:bengkels',
                'city' => 'required|string',
                'province' => 'required|string',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'description' => 'nullable|string',
                'status' => 'required|in:aktif,nonaktif',
                'owner_name' => 'required|string',
                'owner_phone' => 'nullable|string',
            ]);

            $bengkel = Bengkel::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Bengkel created successfully',
                'data' => $bengkel
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Bengkel $bengkel)
    {
        try {
            $bengkel->load(['mechanics', 'vehicles', 'orders']);
            return response()->json([
                'success' => true,
                'message' => 'Bengkel retrieved successfully',
                'data' => $bengkel
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bengkel $bengkel)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|unique:bengkels,name,' . $bengkel->id,
                'address' => 'sometimes|string',
                'phone' => 'sometimes|string',
                'email' => 'sometimes|email|unique:bengkels,email,' . $bengkel->id,
                'city' => 'sometimes|string',
                'province' => 'sometimes|string',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'description' => 'nullable|string',
                'status' => 'sometimes|in:aktif,nonaktif',
                'owner_name' => 'sometimes|string',
                'owner_phone' => 'nullable|string',
            ]);

            $bengkel->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Bengkel updated successfully',
                'data' => $bengkel
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bengkel $bengkel)
    {
        try {
            $bengkel->delete();
            return response()->json([
                'success' => true,
                'message' => 'Bengkel deleted successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}