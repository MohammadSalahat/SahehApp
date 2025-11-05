<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PythonAIService
{
    /**
     * Python AI Service base URL
     */
    protected string $baseUrl;

    /**
     * API timeout in seconds
     */
    protected int $timeout;

    /**
     * Initialize the service
     */
    public function __construct()
    {
        $this->baseUrl = config('services.python_ai.url', 'http://localhost:8000');
        $this->timeout = config('services.python_ai.timeout', 30);
    }

    /**
     * Verify Arabic news using AI semantic similarity
     *
     * @param  string  $text  Arabic news text to verify
     * @param  float  $threshold  Similarity threshold (0.0 to 1.0)
     * @param  int  $topK  Number of top results to return
     * @return array Verification results with similarity scores
     *
     * @throws Exception
     */
    public function verifyArabicNews(string $text, float $threshold = 0.6, int $topK = 5): array
    {
        try {
            Log::info('Sending Arabic news to Python AI for verification', [
                'text_length' => strlen($text),
                'threshold' => $threshold,
                'top_k' => $topK,
            ]);

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'X-API-Key' => config('services.python_ai.api_key'),
                ])
                ->post("{$this->baseUrl}/verify-arabic/check", [
                    'text' => $text,
                    'threshold' => $threshold,
                    'top_k' => $topK,
                ]);

            if (! $response->successful()) {
                Log::error('Python AI verification failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new Exception('Python AI service returned error: '.$response->status());
            }

            $result = $response->json();

            Log::info('Python AI verification completed', [
                'is_potentially_fake' => $result['is_potentially_fake'] ?? false,
                'similar_news_found' => $result['similar_news_found'] ?? 0,
                'highest_similarity' => $result['highest_similarity'] ?? null,
            ]);

            return $result;

        } catch (Exception $e) {
            Log::error('Error calling Python AI service', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Verify Arabic news with pre-fetched candidates (DEADLOCK FIX)
     *
     * This method passes candidate fake news data directly to Python,
     * avoiding the circular dependency deadlock that occurs when:
     * Laravel → Python → Laravel API (blocked on single-threaded server)
     *
     * @param  string  $text  Arabic news text to verify
     * @param  array  $candidates  Array of fake news candidates from database
     * @param  float  $threshold  Similarity threshold (0.0 to 1.0)
     * @param  int  $topK  Number of top results to return
     * @return array Verification results with similarity scores
     *
     * @throws Exception
     */
    public function verifyArabicNewsWithCandidates(
        string $text,
        array $candidates,
        float $threshold = 0.6,
        int $topK = 5
    ): array {
        try {
            Log::info('Sending Arabic news to Python AI with pre-fetched candidates', [
                'text_length' => strlen($text),
                'candidates_count' => count($candidates),
                'threshold' => $threshold,
                'top_k' => $topK,
            ]);

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'X-API-Key' => config('services.python_ai.api_key'),
                ])
                ->post("{$this->baseUrl}/verify-arabic/check-with-candidates", [
                    'text' => $text,
                    'candidates' => $candidates,
                    'threshold' => $threshold,
                    'top_k' => $topK,
                ]);

            if (! $response->successful()) {
                Log::error('Python AI verification failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new Exception('Python AI service returned error: '.$response->status());
            }

            $result = $response->json();

            Log::info('Python AI verification completed (with candidates)', [
                'is_potentially_fake' => $result['is_potentially_fake'] ?? false,
                'similar_news_found' => $result['similar_news_found'] ?? 0,
                'highest_similarity' => $result['highest_similarity'] ?? null,
            ]);

            return $result;

        } catch (Exception $e) {
            Log::error('Error calling Python AI service (with candidates)', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Preprocess Arabic text (for testing)
     *
     * @param  string  $text  Raw Arabic text
     * @return array Preprocessing results
     *
     * @throws Exception
     */
    public function preprocessText(string $text): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'X-API-Key' => config('services.python_ai.api_key'),
                ])
                ->post("{$this->baseUrl}/verify-arabic/preprocess", [
                    'text' => $text,
                ]);

            if (! $response->successful()) {
                throw new Exception('Preprocessing failed: '.$response->status());
            }

            return $response->json();

        } catch (Exception $e) {
            Log::error('Text preprocessing failed', [
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Check if Python AI service is healthy
     */
    public function healthCheck(): bool
    {
        try {
            $response = Http::timeout(5)
                ->withHeaders([
                    'X-API-Key' => config('services.python_ai.api_key'),
                ])
                ->get("{$this->baseUrl}/verify-arabic/health");

            return $response->successful() &&
                   ($response->json()['status'] ?? '') === 'healthy';

        } catch (Exception $e) {
            Log::warning('Python AI health check failed', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Get similarity level in Arabic
     *
     * @param  string  $level  Similarity level (e.g., 'very_high', 'high')
     * @return string Arabic translation
     */
    public static function getSimilarityLevelArabic(string $level): string
    {
        return match ($level) {
            'identical' => 'متطابق تماماً',
            'very_high' => 'تشابه كبير جداً',
            'high' => 'تشابه كبير',
            'medium' => 'تشابه متوسط',
            'low' => 'تشابه منخفض',
            'very_low' => 'تشابه ضعيف جداً',
            default => 'غير معروف'
        };
    }

    /**
     * Get recommendation color based on similarity
     *
     * @param  float|null  $similarity  Similarity score (0.0 to 1.0)
     * @return string CSS color class
     */
    public static function getRecommendationColor(?float $similarity): string
    {
        if ($similarity === null) {
            return 'text-gray-600';
        }

        if ($similarity >= 0.7) {
            return 'text-red-600'; // High similarity - Warning
        } elseif ($similarity >= 0.5) {
            return 'text-orange-600'; // Medium similarity - Caution
        } else {
            return 'text-green-600'; // Low similarity - Safe
        }
    }

    /**
     * Get recommendation icon based on similarity
     *
     * @param  float|null  $similarity  Similarity score (0.0 to 1.0)
     * @return string Icon name
     */
    public static function getRecommendationIcon(?float $similarity): string
    {
        if ($similarity === null) {
            return 'info';
        }

        if ($similarity >= 0.7) {
            return 'warning'; // High similarity - Warning
        } elseif ($similarity >= 0.5) {
            return 'alert'; // Medium similarity - Caution
        } else {
            return 'check'; // Low similarity - Safe
        }
    }
}
