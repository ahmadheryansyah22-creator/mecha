<?php

namespace App\Http\Controllers;

use App\Models\AiDiagnostic;
use App\Services\OllamaService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AiDiagnosticController extends Controller
{
    protected $ollamaService;

    public function __construct(OllamaService $ollamaService)
    {
        $this->ollamaService = $ollamaService;
    }

    public function index()
    {
        try {
            $aiDiagnostics = AiDiagnostic::with(['vehicle', 'diagnostic'])->paginate(10);
            return response()->json([
                'success' => true,
                'message' => 'Data AI diagnostics retrieved successfully',
                'data' => $aiDiagnostics
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
                'diagnostic_id' => 'nullable|exists:diagnostics,id',
                'symptom_description' => 'required|string',
                'ai_findings' => 'nullable|array',
                'recommended_services' => 'nullable|array',
                'recommended_spare_parts' => 'nullable|array',
                'severity_prediction' => 'nullable|in:ringan,sedang,berat',
            ]);

            // Call Ollama AI Service untuk generate analysis
            $aiResponse = $this->ollamaService->diagnose($validated['symptom_description']);

            $validated['ai_analysis'] = $aiResponse['analysis'] ?? 'No analysis available';
            $validated['ai_findings'] = $aiResponse['findings'] ?? [];
            $validated['recommended_services'] = $aiResponse['recommended_services'] ?? [];
            $validated['recommended_spare_parts'] = $aiResponse['recommended_spare_parts'] ?? [];
            $validated['confidence_score'] = $aiResponse['confidence_score'] ?? 0;
            $validated['severity_prediction'] = $aiResponse['severity'] ?? null;
            $validated['ai_model'] = 'llama2';
            $validated['raw_response'] = json_encode($aiResponse);

            $aiDiagnostic = AiDiagnostic::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'AI diagnostic created successfully',
                'data' => $aiDiagnostic
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(AiDiagnostic $aiDiagnostic)
    {
        try {
            $aiDiagnostic->load(['vehicle', 'diagnostic']);
            return response()->json([
                'success' => true,
                'message' => 'AI diagnostic retrieved successfully',
                'data' => $aiDiagnostic
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, AiDiagnostic $aiDiagnostic)
    {
        try {
            $validated = $request->validate([
                'vehicle_id' => 'sometimes|exists:vehicles,id',
                'diagnostic_id' => 'nullable|exists:diagnostics,id',
                'symptom_description' => 'sometimes|string',
                'ai_findings' => 'nullable|array',
                'recommended_services' => 'nullable|array',
                'recommended_spare_parts' => 'nullable|array',
                'severity_prediction' => 'nullable|in:ringan,sedang,berat',
                'is_accurate' => 'nullable|boolean',
                'accuracy_feedback' => 'nullable|integer|min:1|max:5',
            ]);

            $aiDiagnostic->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'AI diagnostic updated successfully',
                'data' => $aiDiagnostic
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(AiDiagnostic $aiDiagnostic)
    {
        try {
            $aiDiagnostic->delete();
            return response()->json([
                'success' => true,
                'message' => 'AI diagnostic deleted successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}