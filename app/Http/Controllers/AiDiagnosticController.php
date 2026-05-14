<?php

namespace App\Http\Controllers;

use App\Models\AiDiagnostic;
use App\Services\OllamaService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class AiDiagnosticController extends Controller
{
    protected $ollamaService;

    public function __construct(OllamaService $ollamaService)
    {
        $this->ollamaService = $ollamaService;
    }

    #[OA\Get(path: "/api/ai-diagnostics", tags: ["AI Diagnostic"], summary: "Get semua AI diagnostics", security: [["token" => []]], responses: [new OA\Response(response: 200, description: "Data AI diagnostics retrieved successfully")])]
    public function index()
    {
        try {
            $aiDiagnostics = AiDiagnostic::with("diagnostic")->paginate(10);
            return response()->json(["success" => true, "message" => "Data AI diagnostics retrieved successfully", "data" => $aiDiagnostics], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Post(path: "/api/ai-diagnostics", tags: ["AI Diagnostic"], summary: "Buat AI diagnostic baru", security: [["token" => []]], responses: [new OA\Response(response: 201, description: "AI Diagnostic created successfully")])]
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                "diagnostic_id" => "nullable|exists:diagnostics,id",
                "symptoms" => "required|string"
            ]);

            $aiResponse = $this->ollamaService->diagnose($validated['symptoms']);

            $aiDiagnostic = AiDiagnostic::create([
                'diagnostic_id' => null,
                'symptoms' => $validated['symptoms'],
                'ai_response' => $aiResponse,
            ]);

            return response()->json([
                "success" => true,
                "message" => "AI Diagnostic created successfully",
                "data" => $aiDiagnostic
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Get(path: "/api/ai-diagnostics/{id}", tags: ["AI Diagnostic"], summary: "Get AI diagnostic by ID", security: [["token" => []]], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))], responses: [new OA\Response(response: 200, description: "AI Diagnostic retrieved successfully")])]
    public function show(AiDiagnostic $aiDiagnostic)
    {
        try {
            $aiDiagnostic->load("diagnostic");
            return response()->json(["success" => true, "message" => "AI Diagnostic retrieved successfully", "data" => $aiDiagnostic], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Delete(path: "/api/ai-diagnostics/{id}", tags: ["AI Diagnostic"], summary: "Hapus AI diagnostic", security: [["token" => []]], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))], responses: [new OA\Response(response: 200, description: "AI Diagnostic deleted successfully")])]
    public function destroy(AiDiagnostic $aiDiagnostic)
    {
        try {
            $aiDiagnostic->delete();
            return response()->json(["success" => true, "message" => "AI Diagnostic deleted successfully"], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}