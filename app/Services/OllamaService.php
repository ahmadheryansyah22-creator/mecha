<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OllamaService
{
    protected $baseUrl;
    protected $model;

    public function __construct()
    {
        $this->baseUrl = 'http://localhost:11434';
        $this->model = 'llama3.2';
    }

    public function diagnose(string $symptoms): string
    {
        try {
            $prompt = "Kamu adalah mekanik mobil profesional Indonesia yang berpengalaman. Analisa keluhan kendaraan berikut dan berikan jawaban dalam Bahasa Indonesia yang mudah dipahami:\n\n$symptoms\n\nBerikan analisa singkat tentang kemungkinan penyebab, tingkat keparahan, dan rekomendasi tindakan.";

            $response = Http::timeout(300)->post($this->baseUrl . '/api/generate', [
                'model' => $this->model,
                'prompt' => $prompt,
                'stream' => false,
                'options' => [
                    'temperature' => 0.7,
                    'num_predict' => 500,
                ]
            ]);

            if ($response->successful()) {
                return $response->json('response') ?? 'Tidak ada respons dari AI.';
            }

            return 'AI tidak dapat memproses permintaan saat ini.';
        } catch (\Exception $e) {
            return 'Gagal terhubung ke AI: ' . $e->getMessage();
        }
    }
}