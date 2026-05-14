<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiGroqController extends Controller
{
    public function diagnose(Request $request)
    {
        try {
            $request->validate([
                "symptoms" => "required|string",
            ]);

            $systemPrompt = "Kamu adalah AI Mekanik khusus milik aplikasi MECHA. Tugasmu HANYA membantu diagnosa masalah kendaraan bermotor (mobil, motor, truk) dan memberikan saran servis bengkel. PENTING: Jika pengguna bertanya tentang hal selain kendaraan, mesin, servis, sparepart, atau bengkel, tolak dengan sopan dan arahkan kembali ke topik kendaraan. Jangan jawab pertanyaan di luar topik otomotif dan bengkel.";

            $prompt = "Kendaraan: " . ($request->vehicle_info ?? "tidak disebutkan") . "\nKeluhan: " . $request->symptoms . "\n\nJika keluhan di atas bukan tentang kendaraan atau bengkel, balas dengan: \"Maaf, saya hanya bisa membantu diagnosa masalah kendaraan dan servis bengkel. Silakan ceritakan keluhan kendaraan kamu.\"\n\nJika memang tentang kendaraan, berikan analisa dengan format:\n\n?? KEMUNGKINAN PENYEBAB:\n(jelaskan 2-3 kemungkinan penyebab utama)\n\n?? TINGKAT KEPARAHAN:\n(Ringan/Sedang/Berat - jelaskan alasannya)\n\n?? REKOMENDASI TINDAKAN:\n(langkah-langkah yang harus dilakukan)\n\n?? ESTIMASI BIAYA:\n(perkiraan biaya perbaikan dalam Rupiah)\n\n??? TIPS PENCEGAHAN:\n(cara mencegah masalah serupa di masa depan)";

            $response = Http::withHeaders([
                "Authorization" => "Bearer " . env("GROQ_API_KEY"),
                "Content-Type" => "application/json",
            ])->post("https://api.groq.com/openai/v1/chat/completions", [
                "model" => "llama-3.3-70b-versatile",
                "messages" => [
                    ["role" => "system", "content" => $systemPrompt],
                    ["role" => "user", "content" => $prompt]
                ],
                "max_tokens" => 1000,
                "temperature" => 0.7,
            ]);

            if ($response->failed()) {
                return response()->json([
                    "success" => false,
                    "message" => "Groq API error: " . $response->body()
                ], 500);
            }

            $result = $response->json("choices.0.message.content");

            return response()->json([
                "success" => true,
                "result" => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}
