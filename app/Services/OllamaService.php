<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OllamaService
{
    protected $baseUrl = 'http://localhost:11434';
    protected $model = 'llama2';

    /**
     * Call Ollama API untuk diagnosa kendaraan
     */
    public function diagnose(string $symptoms): array
    {
        try {
            $prompt = $this->buildDiagnosisPrompt($symptoms);

            $response = Http::timeout(60)->post($this->baseUrl . '/api/generate', [
                'model' => $this->model,
                'prompt' => $prompt,
                'stream' => false,
            ]);

            if ($response->successful()) {
                return $this->parseResponse($response->json());
            }

            return [
                'analysis' => 'Unable to generate diagnosis',
                'confidence_score' => 0,
                'findings' => [],
            ];
        } catch (\Exception $e) {
            return [
                'analysis' => 'Error: ' . $e->getMessage(),
                'confidence_score' => 0,
                'findings' => [],
            ];
        }
    }

    /**
     * Build prompt untuk Ollama
     */
    protected function buildDiagnosisPrompt(string $symptoms): string
    {
        return "Anda adalah ahli mekanik mobil berpengalaman. Berdasarkan gejala berikut, lakukan diagnosa lengkap:

GEJALA KENDARAAN:
{$symptoms}

Berikan respons dalam format JSON dengan field berikut:
{
    \"analysis\": \"analisis detail tentang masalah\",
    \"findings\": [\"temuan 1\", \"temuan 2\"],
    \"recommended_services\": [\"service 1\", \"service 2\"],
    \"recommended_spare_parts\": [\"part 1\", \"part 2\"],
    \"severity\": \"ringan/sedang/berat\",
    \"confidence_score\": 85
}

RESPONS:";
    }

    /**
     * Parse response dari Ollama
     */
    protected function parseResponse(array $response): array
    {
        try {
            $text = $response['response'] ?? '';
            
            // Extract JSON dari response
            preg_match('/\{[\s\S]*\}/', $text, $matches);
            
            if (!empty($matches)) {
                $data = json_decode($matches[0], true);
                
                return [
                    'analysis' => $data['analysis'] ?? 'Diagnosis generated',
                    'findings' => $data['findings'] ?? [],
                    'recommended_services' => $data['recommended_services'] ?? [],
                    'recommended_spare_parts' => $data['recommended_spare_parts'] ?? [],
                    'severity' => $data['severity'] ?? 'sedang',
                    'confidence_score' => $data['confidence_score'] ?? 75,
                ];
            }

            return [
                'analysis' => $text,
                'confidence_score' => 50,
                'findings' => [],
            ];
        } catch (\Exception $e) {
            return [
                'analysis' => 'Error parsing response: ' . $e->getMessage(),
                'confidence_score' => 0,
                'findings' => [],
            ];
        }
    }
}