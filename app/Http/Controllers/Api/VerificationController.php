<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DatasetFakeNews;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    /**
     * Verify an article by checking against local dataset and AI fallback.
     * This endpoint is called by the frontend, then Laravel calls Python service.
     */
    public function verify(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // First, check local database using fulltext search
            $searchQuery = $request->input('title').' '.$request->input('content');
            $matches = DatasetFakeNews::searchFulltext($searchQuery)
                ->minimumConfidence(0.7)
                ->limit(5)
                ->get();

            if ($matches->isNotEmpty()) {
                // Found in local database
                $bestMatch = $matches->first();

                return response()->json([
                    'success' => true,
                    'result' => 'fake',
                    'confidence_score' => $bestMatch->confidence_score,
                    'source' => 'local_database',
                    'matched_entry' => [
                        'id' => $bestMatch->id,
                        'title' => $bestMatch->title,
                        'origin_dataset' => $bestMatch->origin_dataset_name,
                    ],
                    'message' => 'Article found in fake news database',
                ], 200);
            }

            // Not found in local database, call Python AI service
            $pythonResponse = $this->callPythonService($request->input('title'), $request->input('content'));

            return response()->json([
                'success' => true,
                'result' => $pythonResponse['result'] ?? 'unknown',
                'confidence_score' => $pythonResponse['confidence_score'] ?? 0,
                'source' => 'ai_verification',
                'ai_provider' => $pythonResponse['ai_provider'] ?? 'unknown',
                'sources_checked' => $pythonResponse['sources_checked'] ?? [],
                'message' => $pythonResponse['message'] ?? 'Article verified by AI service',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Verification failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Call Python AI service for verification.
     */
    private function callPythonService(string $title, string $content): array
    {
        $pythonUrl = config('services.python_ai.url');
        $apiKey = config('services.python_ai.api_key');
        $timeout = config('services.python_ai.timeout', 30);

        try {
            $response = Http::timeout($timeout)
                ->withHeaders([
                    'X-API-Key' => $apiKey,
                    'Accept' => 'application/json',
                ])
                ->post("{$pythonUrl}/api/v1/verification/verify", [
                    'title' => $title,
                    'content' => $content,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Python service returned error: '.$response->body());
        } catch (\Exception $e) {
            // Fallback response if Python service is unavailable
            return [
                'result' => 'unknown',
                'confidence_score' => 0,
                'message' => 'AI service unavailable. Please try again later.',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Health check endpoint to verify Python service connectivity.
     */
    public function healthCheck(): JsonResponse
    {
        try {
            $pythonUrl = config('services.python_ai.url');
            $apiKey = config('services.python_ai.api_key');

            $response = Http::timeout(5)
                ->withHeaders([
                    'X-API-Key' => $apiKey,
                ])
                ->get("{$pythonUrl}/api/v1/health");

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Python AI service is healthy',
                    'python_service' => $response->json(),
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Python AI service returned error',
                'status_code' => $response->status(),
            ], 503);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot connect to Python AI service',
                'error' => $e->getMessage(),
            ], 503);
        }
    }
}
