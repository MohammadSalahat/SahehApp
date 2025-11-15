<?php

namespace App\Http\Controllers\Web;

use App\Helpers\LanguageDetector;
use App\Http\Controllers\Controller;
use App\Models\ChatGPTVerification;
use App\Services\ChatGPTService;
use App\Services\PythonAIService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    /**
     * Python AI Service instance
     */
    protected PythonAIService $pythonAI;

    /**
     * ChatGPT Service instance
     */
    protected ChatGPTService $chatGPT;

    /**
     * Initialize controller with AI services
     */
    public function __construct(PythonAIService $pythonAI, ChatGPTService $chatGPT)
    {
        $this->pythonAI = $pythonAI;
        $this->chatGPT = $chatGPT;
    }

    /**
     * Verify news article submitted by user using AI semantic similarity.
     *
     * ARCHITECTURE NOTE:
     * To avoid deadlock with single-threaded php artisan serve, we:
     * 1. Fetch fake news candidates from Laravel database first
     * 2. Send both the user text AND candidate data to Python
     * 3. Python processes locally without calling back to Laravel
     *
     * This eliminates the circular dependency:
     * OLD (deadlock): Laravel → Python → Laravel API (❌ blocked)
     * NEW (fast):     Laravel (fetch data) → Python (process) → Response ✅
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:20|max:10000',
        ], [
            'content.required' => 'الرجاء إدخال نص الخبر | Please enter the news text',
            'content.min' => 'النص قصير جداً. الحد الأدنى 20 حرف | Text is too short. Minimum 20 characters',
            'content.max' => 'النص طويل جداً. الحد الأقصى 10000 حرف | Text is too long. Maximum 10,000 characters',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $content = $request->input('content');

        try {
            // Detect language
            $detectedLanguage = LanguageDetector::detect($content);
            $isArabic = $detectedLanguage === 'ar';

            Log::info('Starting verification', [
                'text_length' => strlen($content),
                'word_count' => str_word_count($content),
                'detected_language' => $detectedLanguage,
            ]);

            // Route to appropriate verification method based on language
            if ($isArabic) {
                // ARABIC: Use AI-powered semantic similarity with AraBERT
                $aiResult = $this->verifyArabicContent($content);
            } else {
                // ENGLISH: Use direct FULLTEXT matching (faster, no AI needed)
                $aiResult = $this->pythonAI->verifyEnglishNews(
                    text: $content,
                    threshold: 0.70,
                    topK: 5
                );
            }

            Log::info('Verification completed', [
                'language' => $detectedLanguage,
                'method' => $isArabic ? 'arabic_ai_semantic' : 'english_fulltext',
                'is_potentially_fake' => $aiResult['is_potentially_fake'],
                'similar_news_found' => $aiResult['similar_news_found'],
                'highest_similarity' => $aiResult['highest_similarity'],
            ]);

            // FALLBACK TO CHATGPT if no similarity found
            $chatGPTResult = null;
            $usedFallback = false;
            $fallbackThreshold = config('chatgpt_prompts.fallback_threshold', 0.70);

            if ($aiResult['similar_news_found'] === 0 || $aiResult['highest_similarity'] < $fallbackThreshold) {
                Log::info('No similarity found in database, using ChatGPT fallback', [
                    'similar_news_found' => $aiResult['similar_news_found'],
                    'highest_similarity' => $aiResult['highest_similarity'],
                ]);

                try {
                    if ($this->chatGPT->isAvailable()) {
                        $startTime = microtime(true);
                        
                        $chatGPTResult = $this->chatGPT->verifyNews($content);
                        $processingTime = (microtime(true) - $startTime) * 1000; // Convert to ms

                        // Log to database
                        ChatGPTVerification::create([
                            'original_text' => $content,
                            'language' => $detectedLanguage,
                            'category' => $chatGPTResult['category'] ?? null,
                            'model_used' => $chatGPTResult['model_used'] ?? 'gpt-4',
                            'is_potentially_fake' => $chatGPTResult['is_potentially_fake'],
                            'confidence_score' => $chatGPTResult['confidence_score'],
                            'credibility_level' => $chatGPTResult['credibility_level'],
                            'analysis' => $chatGPTResult['analysis'],
                            'warning_signs' => $chatGPTResult['warning_signs'],
                            'recommendation' => $chatGPTResult['recommendation'],
                            'verification_tips' => $chatGPTResult['verification_tips'],
                            'related_topics' => $chatGPTResult['related_topics'],
                            'fact_check_sources' => $chatGPTResult['fact_check_sources'],
                            'sources_checked' => $chatGPTResult['sources_checked'] ?? [],
                            'source_verification_status' => $chatGPTResult['source_verification_status'] ?? [],
                            'trusted_sources_used' => $chatGPTResult['trusted_sources_used'] ?? [],
                            'tokens_used' => $chatGPTResult['tokens_used'],
                            'processing_time_ms' => $processingTime,
                            'user_ip' => $request->ip(),
                            'user_id' => auth()->id(),
                            'status' => 'completed',
                        ]);

                        $usedFallback = true;

                        Log::info('ChatGPT fallback verification completed with strict rule', [
                            'is_potentially_fake' => $chatGPTResult['is_potentially_fake'],
                            'confidence' => $chatGPTResult['confidence_score'],
                            'tokens_used' => $chatGPTResult['tokens_used'],
                            'found_in_sources' => $chatGPTResult['source_verification_status']['found_in_sources'] ?? false,
                            'sources_searched' => $chatGPTResult['source_verification_status']['sources_searched'] ?? 0,
                            'strict_rule_applied' => true,
                        ]);
                    } else {
                        Log::warning('ChatGPT fallback not available');
                    }
                } catch (Exception $e) {
                    Log::error('ChatGPT fallback failed', [
                        'error' => $e->getMessage(),
                    ]);
                    
                    // Log failed attempt
                    ChatGPTVerification::create([
                        'original_text' => $content,
                        'language' => $detectedLanguage,
                        'user_ip' => $request->ip(),
                        'user_id' => auth()->id(),
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);
                }
            }

            // Prepare data for view
            // IMPORTANT: When ChatGPT strict verification is used, prioritize its results
            $isFake = $aiResult['is_potentially_fake'];
            $recommendation = $aiResult['recommendation'];
            $processingMethod = $aiResult['processing_method'] ?? ($isArabic ? 'arabic_ai_semantic' : 'unknown');

            // Override with ChatGPT strict verification results if fallback was used
            if ($usedFallback && $chatGPTResult) {
                $isFake = $chatGPTResult['is_potentially_fake'];
                $recommendation = $chatGPTResult['recommendation'][$detectedLanguage] ?? $chatGPTResult['recommendation']['ar'] ?? 'يُرجى التحقق من المصادر الرسمية';
                $processingMethod = 'chatgpt_strict_verification';
            }

            $viewData = [
                'search_content' => $content,
                'ai_result' => $aiResult,
                'found' => $aiResult['similar_news_found'] > 0,
                'is_potentially_fake' => $isFake,
                'similar_news' => $aiResult['similar_news'] ?? [],
                'highest_similarity' => $aiResult['highest_similarity'],
                'recommendation' => $recommendation,
                'query_quality' => $aiResult['query_quality'] ?? [],
                'preprocessed_text' => $aiResult['query_text_preprocessed'] ?? '',
                'detected_language' => $detectedLanguage,
                'processing_method' => $processingMethod,
                'chatgpt_result' => $chatGPTResult,
                'used_chatgpt_fallback' => $usedFallback,
            ];

            // If similar news found, add best match details
            if (! empty($aiResult['similar_news'])) {
                $bestMatch = $aiResult['similar_news'][0];

                $viewData['best_match'] = [
                    'id' => $bestMatch['id'],
                    'title' => $bestMatch['title'],
                    'content' => $bestMatch['content'],
                    'similarity_score' => $bestMatch['similarity_score'],
                    'similarity_level' => $bestMatch['similarity_level'] ?? 'unknown',
                    'similarity_level_arabic' => PythonAIService::getSimilarityLevelArabic($bestMatch['similarity_level'] ?? 'unknown'),
                    'confidence_score' => $bestMatch['confidence_score'],
                    'origin_dataset' => $bestMatch['origin_dataset_name'],
                    'recommendation' => $bestMatch['recommendation'] ?? 'يُرجى التحقق من المصادر الرسمية',
                ];
            }

            return view('verification-result', $viewData);

        } catch (Exception $e) {
            Log::error('AI verification failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Fallback: Return error view
            return view('verification-result', [
                'search_content' => $content,
                'found' => false,
                'error' => true,
                'error_message' => 'عذراً، حدث خطأ في نظام التحقق الذكي. الرجاء المحاولة مرة أخرى لاحقاً. | Sorry, an error occurred in the verification system. Please try again later.',
                'error_details' => config('app.debug') ? $e->getMessage() : null,
            ]);
        }
    }

    /**
     * Verify Arabic content using AI semantic similarity with AraBERT
     *
     * @param  string  $content  Arabic text to verify
     * @return array AI verification results
     *
     * @throws Exception
     */
    private function verifyArabicContent(string $content): array
    {
        // DEADLOCK FIX: Fetch fake news candidates HERE in Laravel
        // This prevents Python from calling back to Laravel API
        //
        // IMPROVED MATCHING STRATEGY:
        // 1. Use FULLTEXT search to find exact/close matches first
        // 2. Add random samples to reach 100 candidates for diversity
        // This ensures exact matches are ALWAYS included while maintaining good coverage

        $candidates = [];

        // Step 1: FULLTEXT search for exact/close matches (prioritize these!)
        $searchTerms = mb_substr($content, 0, 500); // Use first 500 chars for search
        $fullTextMatches = \App\Models\DatasetFakeNews::query()
            ->select('id', 'title', 'content', 'confidence_score', 'origin_dataset_name')
            ->where('language', 'ar') // Only Arabic content
            ->where('confidence_score', '>=', 0.5)
            ->whereRaw('MATCH(title, content) AGAINST(? IN NATURAL LANGUAGE MODE)', [$searchTerms])
            ->limit(50)  // Get top 50 FULLTEXT matches
            ->get()
            ->toArray();

        $candidates = $fullTextMatches;
        Log::info('FULLTEXT search found '.count($fullTextMatches).' close matches');

        // Step 2: Add random high-quality entries to reach 100 (for semantic diversity)
        $remainingSlots = 100 - count($candidates);
        if ($remainingSlots > 0) {
            $existingIds = array_column($candidates, 'id');
            $randomSamples = \App\Models\DatasetFakeNews::query()
                ->select('id', 'title', 'content', 'confidence_score', 'origin_dataset_name')
                ->where('language', 'ar') // Only Arabic content
                ->where('confidence_score', '>=', 0.5)
                ->whereNotIn('id', $existingIds)  // Exclude already selected
                ->inRandomOrder()
                ->limit($remainingSlots)
                ->get()
                ->toArray();

            $candidates = array_merge($candidates, $randomSamples);
        }

        Log::info('Fetched Arabic fake news candidates', [
            'count' => count($candidates),
        ]);

        // Call Python AI service with BOTH text and candidates
        // Python will process locally without calling Laravel
        return $this->pythonAI->verifyArabicNewsWithCandidates(
            text: $content,
            candidates: $candidates,
            threshold: 0.70,  // 70% similarity threshold for better accuracy
            topK: 5           // Return top 5 similar news
        );
    }
}
