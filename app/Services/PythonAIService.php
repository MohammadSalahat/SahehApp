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
     * Verify English news using direct FULLTEXT matching (no AI)
     *
     * For English texts, we don't use AraBERT model. Instead, we do
     * direct database matching using MySQL FULLTEXT search for fast results.
     *
     * @param  string  $text  English news text to verify
     * @param  float  $threshold  Similarity threshold (0.0 to 1.0) - not used for English
     * @param  int  $topK  Number of top results to return
     * @return array Verification results with match scores
     */
    public function verifyEnglishNews(string $text, float $threshold = 0.6, int $topK = 5): array
    {
        try {
            Log::info('Verifying English news using FULLTEXT matching', [
                'text_length' => strlen($text),
                'top_k' => $topK,
            ]);

            // Use FULLTEXT search for English content
            $searchTerms = mb_substr($text, 0, 500); // Use first 500 chars

            $matches = \App\Models\DatasetFakeNews::query()
                ->select('id', 'title', 'content', 'confidence_score', 'origin_dataset_name', 'language')
                ->where('language', 'en')
                ->where('confidence_score', '>=', 0.5)
                ->whereRaw('MATCH(title, content) AGAINST(? IN NATURAL LANGUAGE MODE)', [$searchTerms])
                ->limit($topK)
                ->get();

            $similarNews = [];
            $highestScore = 0.0;

            foreach ($matches as $index => $match) {
                // Calculate a pseudo-similarity score based on FULLTEXT relevance
                // In MySQL FULLTEXT, results are ordered by relevance
                // We'll assign declining scores: first result gets highest score
                $score = 0.95 - ($index * 0.1); // 0.95, 0.85, 0.75, 0.65, 0.55
                $score = max($score, 0.55); // Minimum score of 0.55

                if ($score > $highestScore) {
                    $highestScore = $score;
                }

                $similarNews[] = [
                    'id' => $match->id,
                    'title' => $match->title,
                    'content' => $match->content,
                    'similarity_score' => $score,
                    'similarity_level' => $this->getSimilarityLevel($score),
                    'confidence_score' => $match->confidence_score,
                    'origin_dataset_name' => $match->origin_dataset_name,
                    'language' => $match->language,
                    'recommendation' => $this->getRecommendation($score),
                ];
            }

            $isPotentiallyFake = $highestScore >= 0.70;
            $found = count($similarNews) > 0;

            return [
                'is_potentially_fake' => $isPotentiallyFake,
                'similar_news_found' => count($similarNews),
                'highest_similarity' => $highestScore,
                'similar_news' => $similarNews,
                'recommendation' => $isPotentiallyFake
                    ? 'Warning: This content closely matches known fake news in our database. Please verify from official sources.'
                    : ($found
                        ? 'Caution: Some similarity found. Cross-check with reliable sources before sharing.'
                        : 'No significant matches found in our database. Still verify from trusted sources.'),
                'query_quality' => [
                    'word_count' => str_word_count($text),
                    'character_count' => mb_strlen($text),
                    'language' => 'en',
                    'language_name' => 'English',
                ],
                'query_text_preprocessed' => $text, // No preprocessing for English
                'processing_method' => 'fulltext_matching',
            ];

        } catch (Exception $e) {
            Log::error('English news verification failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Get similarity level from score
     */
    private function getSimilarityLevel(float $score): string
    {
        if ($score >= 0.95) {
            return 'identical';
        } elseif ($score >= 0.85) {
            return 'very_high';
        } elseif ($score >= 0.75) {
            return 'high';
        } elseif ($score >= 0.65) {
            return 'medium';
        } elseif ($score >= 0.55) {
            return 'low';
        } else {
            return 'very_low';
        }
    }

    /**
     * Get recommendation based on score
     */
    private function getRecommendation(float $score): string
    {
        if ($score >= 0.85) {
            return 'Highly likely to be fake news. Do not share without verification.';
        } elseif ($score >= 0.70) {
            return 'Potentially fake news. Verify from official sources before believing or sharing.';
        } elseif ($score >= 0.55) {
            return 'Some similarities detected. Cross-check with multiple reliable sources.';
        } else {
            return 'Low similarity. Still recommended to verify from trusted sources.';
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
