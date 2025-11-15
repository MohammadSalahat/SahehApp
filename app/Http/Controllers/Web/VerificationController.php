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

            // FALLBACK TO CHATGPT only if NO database matches found
            // IMPORTANT: Trust database matches over external APIs for better performance and accuracy
            $chatGPTResult = null;
            $usedFallback = false;
            $fallbackThreshold = config('chatgpt_prompts.fallback_threshold', 0.30); // Lowered threshold

            $processingMethod = $aiResult['processing_method'] ?? 'unknown';
            $shouldUseFallback = (
                $aiResult['similar_news_found'] === 0 ||
                ($aiResult['highest_similarity'] < $fallbackThreshold && $processingMethod !== 'exact_database_match' && $processingMethod !== 'optimized_database_search')
            );

            if ($shouldUseFallback) {
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
     * Verify Arabic content using optimized database matching with AI fallback
     *
     * @param  string  $content  Arabic text to verify
     * @return array AI verification results
     *
     * @throws Exception
     */
    private function verifyArabicContent(string $content): array
    {
        // PERFORMANCE OPTIMIZATION: Check exact matches FIRST before AI processing
        // This drastically improves speed and accuracy for known content

        Log::info('Starting Arabic content verification with optimized matching');

        // Step 1: EXACT CONTENT MATCH (fastest and most accurate)
        // Use content hash for faster exact matching
        $contentHash = hash('sha256', trim($content));
        $exactMatch = \App\Models\DatasetFakeNews::query()
            ->select('id', 'title', 'content', 'confidence_score', 'origin_dataset_name')
            ->where('language', 'ar')
            ->where('content_hash', $contentHash)
            ->first();

        // Fallback to direct content comparison if hash doesn't match
        if (! $exactMatch) {
            $exactMatch = \App\Models\DatasetFakeNews::query()
                ->select('id', 'title', 'content', 'confidence_score', 'origin_dataset_name')
                ->where('language', 'ar')
                ->where('content', trim($content))
                ->first();
        }

        if ($exactMatch) {
            Log::info('EXACT MATCH FOUND - Skipping AI processing', [
                'id' => $exactMatch->id,
                'confidence_score' => $exactMatch->confidence_score,
                'title' => mb_substr($exactMatch->title, 0, 50).'...',
            ]);

            // Calculate inverse confidence for real news detection
            $realNewsConfidence = 1.0 - $exactMatch->confidence_score;

            return [
                'is_potentially_fake' => $exactMatch->confidence_score > 0.5, // >50% = fake
                'similar_news_found' => 1,
                'highest_similarity' => 1.0, // Exact match = 100% similarity
                'similar_news' => [
                    [
                        'id' => $exactMatch->id,
                        'title' => $exactMatch->title,
                        'content' => $exactMatch->content,
                        'similarity_score' => 1.0,
                        'similarity_level' => 'exact_match',
                        'confidence_score' => $exactMatch->confidence_score,
                        'real_news_confidence' => $realNewsConfidence,
                        'origin_dataset_name' => $exactMatch->origin_dataset_name,
                        'recommendation' => $realNewsConfidence > 0.8
                            ? 'هذا الخبر موثق في قاعدة بياناتنا بدرجة عالية من الموثوقية'
                            : 'هذا الخبر موجود في قاعدة بياناتنا - يُرجى التحقق من المصادر',
                    ],
                ],
                'recommendation' => $realNewsConfidence > 0.8
                    ? 'هذا الخبر موثق في قاعدة بياناتنا بدرجة عالية من الموثوقية ('.round($realNewsConfidence * 100).'%)'
                    : 'هذا الخبر موجود في قاعدة بياناتنا - يُرجى التحقق من المصادر الرسمية',
                'processing_method' => 'exact_database_match',
                'query_quality' => ['exact_match_found' => true],
                'query_text_preprocessed' => $content,
            ];
        }

        // Step 2: SEMANTIC SIMILARITY SEARCH - Enhanced for paraphrased content
        // Extract key entities and concepts for better matching
        $contentWords = preg_split('/\s+/', trim($content));
        $significantWords = array_filter($contentWords, function ($word) {
            return mb_strlen($word) > 3; // Filter out short words
        });

        if (count($significantWords) >= 3) {
            Log::info('Starting semantic keyword matching', [
                'significant_words_count' => count($significantWords),
                'sample_words' => array_slice($significantWords, 0, 5),
            ]);

            // Strategy 1: Find potential matches using key entities
            $potentialMatches = \App\Models\DatasetFakeNews::query()
                ->select('id', 'title', 'content', 'confidence_score', 'origin_dataset_name')
                ->where('language', 'ar');

            // SEMANTIC KEYWORD EXPANSION: Include synonymous terms for better matching
            $synonymMap = [
                'معدلات' => ['أسعار', 'معدلات'],  // rates/prices
                'أسعار' => ['معدلات', 'أسعار'],   // prices/rates
                'فائدة' => ['فوائد', 'فائدة'],     // interest/interests
                'أفاد' => ['أعلن', 'صرح', 'أكد', 'ذكر', 'أوضح'], // reporting verbs
                'أعلن' => ['أفاد', 'صرح', 'أكد', 'ذكر', 'أوضح'], // announcement verbs
                'السوق' => ['الأسواق', 'السوق', 'الاقتصاد'], // market terms
                'المالي' => ['المالية', 'المصرفي', 'الاقتصادي'], // financial terms
            ];

            // Expand keywords with their synonyms
            $expandedKeywords = [];
            foreach (array_slice($significantWords, 0, 6) as $word) {
                $expandedKeywords[] = $word;
                if (isset($synonymMap[$word])) {
                    $expandedKeywords = array_merge($expandedKeywords, $synonymMap[$word]);
                }
            }
            $expandedKeywords = array_unique($expandedKeywords);

            Log::info('Expanded keywords for semantic search', [
                'original' => array_slice($significantWords, 0, 6),
                'expanded' => $expandedKeywords,
            ]);

            // Score-based matching with semantic expansion
            $allCandidates = [];

            foreach ($expandedKeywords as $word) {
                $wordMatches = \App\Models\DatasetFakeNews::query()
                    ->select('id', 'title', 'content', 'confidence_score', 'origin_dataset_name')
                    ->where('language', 'ar')
                    ->where('content', 'LIKE', '%'.$word.'%')
                    ->limit(15)
                    ->get();

                foreach ($wordMatches as $match) {
                    if (! isset($allCandidates[$match->id])) {
                        $allCandidates[$match->id] = [
                            'record' => $match,
                            'keyword_score' => 0,
                        ];
                    }
                    // Give higher score to original keywords vs synonyms
                    $scoreIncrement = in_array($word, array_slice($significantWords, 0, 6)) ? 2 : 1;
                    $allCandidates[$match->id]['keyword_score'] += $scoreIncrement;
                }
            }

            // Sort by keyword score (records with more matching keywords first)
            uasort($allCandidates, function ($a, $b) {
                return $b['keyword_score'] <=> $a['keyword_score'];
            });

            $candidatesForSimilarity = collect(array_slice($allCandidates, 0, 40))->pluck('record');

            if ($candidatesForSimilarity->count() > 0) {
                Log::info('Found potential semantic matches', ['count' => $candidatesForSimilarity->count()]);

                // Calculate enhanced similarity for each candidate
                $matches = [];
                foreach ($candidatesForSimilarity as $candidate) {
                    $similarity = $this->calculateEnhancedSimilarity($content, $candidate->content);

                    // LOWERED THRESHOLD for paraphrased content recognition
                    if ($similarity > 0.25) { // 25% similarity threshold (much more flexible)
                        $realConfidence = 1.0 - $candidate->confidence_score;

                        $matches[] = [
                            'id' => $candidate->id,
                            'title' => $candidate->title,
                            'content' => $candidate->content,
                            'similarity_score' => $similarity,
                            'similarity_level' => $this->getSimilarityLevel($similarity),
                            'confidence_score' => $candidate->confidence_score,
                            'real_news_confidence' => $realConfidence,
                            'origin_dataset_name' => $candidate->origin_dataset_name,
                            'recommendation' => $this->generateRecommendation($similarity, $realConfidence),
                        ];
                    }
                }

                if (! empty($matches)) {
                    // Sort by similarity descending
                    usort($matches, function ($a, $b) {
                        return $b['similarity_score'] <=> $a['similarity_score'];
                    });

                    $bestMatch = $matches[0];

                    Log::info('Found semantic similarity match', [
                        'best_similarity' => $bestMatch['similarity_score'],
                        'matches_count' => count($matches),
                        'match_id' => $bestMatch['id'],
                    ]);

                    return [
                        'is_potentially_fake' => $bestMatch['confidence_score'] > 0.5,
                        'similar_news_found' => count($matches),
                        'highest_similarity' => $bestMatch['similarity_score'],
                        'similar_news' => $matches,
                        'recommendation' => $bestMatch['recommendation'],
                        'processing_method' => 'enhanced_semantic_search',
                        'query_quality' => ['semantic_matches_found' => count($matches)],
                        'query_text_preprocessed' => $content,
                    ];
                }
            }
        }

        // Step 3: AI SEMANTIC SEARCH (fallback for complex cases)
        Log::info('No direct matches found, using AI semantic search as fallback');

        // Get diverse candidates for AI processing
        $candidates = [];

        // FULLTEXT search for potential matches
        $searchTerms = mb_substr($content, 0, 500);
        $fullTextMatches = \App\Models\DatasetFakeNews::query()
            ->select('id', 'title', 'content', 'confidence_score', 'origin_dataset_name')
            ->where('language', 'ar')
            ->whereRaw('MATCH(title, content) AGAINST(? IN NATURAL LANGUAGE MODE)', [$searchTerms])
            ->limit(50)
            ->get()
            ->toArray();

        $candidates = $fullTextMatches;

        // Add random samples for diversity
        $remainingSlots = 100 - count($candidates);
        if ($remainingSlots > 0) {
            $existingIds = array_column($candidates, 'id');
            $randomSamples = \App\Models\DatasetFakeNews::query()
                ->select('id', 'title', 'content', 'confidence_score', 'origin_dataset_name')
                ->where('language', 'ar')
                ->whereNotIn('id', $existingIds)
                ->inRandomOrder()
                ->limit($remainingSlots)
                ->get()
                ->toArray();

            $candidates = array_merge($candidates, $randomSamples);
        }

        Log::info('Using AI semantic search with candidates', [
            'candidates_count' => count($candidates),
        ]);

        // Call Python AI service as fallback
        return $this->pythonAI->verifyArabicNewsWithCandidates(
            text: $content,
            candidates: $candidates,
            threshold: 0.70,
            topK: 5
        );
    }

    /**
     * Calculate simple text similarity using common words ratio
     *
     * @return float Similarity score between 0 and 1
     */
    private function calculateSimpleSimilarity(string $text1, string $text2): float
    {
        // Normalize texts
        $text1 = mb_strtolower(trim($text1));
        $text2 = mb_strtolower(trim($text2));

        // Extract words
        $words1 = array_filter(preg_split('/\s+/', $text1));
        $words2 = array_filter(preg_split('/\s+/', $text2));

        if (empty($words1) || empty($words2)) {
            return 0.0;
        }

        // Calculate intersection
        $intersection = array_intersect($words1, $words2);
        $union = array_unique(array_merge($words1, $words2));

        // Jaccard similarity
        return count($intersection) / count($union);
    }

    /**
     * Calculate enhanced similarity with weighted terms and semantic understanding
     *
     * @return float Similarity score between 0 and 1
     */
    private function calculateEnhancedSimilarity(string $text1, string $text2): float
    {
        // Normalize texts
        $text1 = mb_strtolower(trim($text1));
        $text2 = mb_strtolower(trim($text2));

        // Extract words
        $words1 = array_filter(preg_split('/\s+/', $text1));
        $words2 = array_filter(preg_split('/\s+/', $text2));

        if (empty($words1) || empty($words2)) {
            return 0.0;
        }

        // Define high-importance terms with contextual categories
        $entityTerms = ['البنك', 'المركزي', 'السعودي', 'ساما', 'الحكومة', 'الملك', 'المملكة', 'الرياض', 'جدة', 'مكة', 'وزارة'];
        $actionTerms = ['أعلن', 'أفاد', 'صرح', 'أكد', 'ذكر', 'أوضح', 'أشار'];
        $topicTerms = ['فائدة', 'أسعار', 'معدلات', 'السوق', 'المالي', 'المالية', 'المصرفي', 'القطاع', 'النظام'];

        $allImportantTerms = array_merge($entityTerms, $actionTerms, $topicTerms);

        $intersection = array_intersect($words1, $words2);
        $union = array_unique(array_merge($words1, $words2));

        // Basic Jaccard similarity
        $basicSimilarity = count($intersection) / count($union);

        // CONTEXTUAL SIMILARITY: Weight different types of terms differently
        $entityMatches = 0;
        $actionMatches = 0;
        $topicMatches = 0;

        $totalEntities = 0;
        $totalActions = 0;
        $totalTopics = 0;

        // Count entity matches (e.g., "البنك المركزي السعودي")
        foreach ($entityTerms as $term) {
            $inText1 = in_array($term, $words1);
            $inText2 = in_array($term, $words2);
            if ($inText1 || $inText2) {
                $totalEntities++;
                if ($inText1 && $inText2) {
                    $entityMatches++;
                }
            }
        }

        // Count action matches (e.g., "أعلن" vs "أفاد")
        foreach ($actionTerms as $term) {
            $inText1 = in_array($term, $words1);
            $inText2 = in_array($term, $words2);
            if ($inText1 || $inText2) {
                $totalActions++;
                if ($inText1 && $inText2) {
                    $actionMatches++;
                }
            }
        }

        // Count topic matches (most important for content relevance)
        foreach ($topicTerms as $term) {
            $inText1 = in_array($term, $words1);
            $inText2 = in_array($term, $words2);
            if ($inText1 || $inText2) {
                $totalTopics++;
                if ($inText1 && $inText2) {
                    $topicMatches++;
                }
            }
        }

        // Calculate contextual weighted score
        $weightedScore = $basicSimilarity;

        // Topic similarity is most important (50% weight)
        if ($totalTopics > 0) {
            $topicBonus = ($topicMatches / $totalTopics) * 0.5;
            $weightedScore += $topicBonus;
        }

        // Entity similarity is medium importance (20% weight)
        if ($totalEntities > 0) {
            $entityBonus = ($entityMatches / $totalEntities) * 0.2;
            $weightedScore += $entityBonus;
        }

        // Action similarity is lower importance (10% weight)
        if ($totalActions > 0) {
            $actionBonus = ($actionMatches / $totalActions) * 0.1;
            $weightedScore += $actionBonus;
        }

        return min(1.0, $weightedScore); // Ensure it doesn't exceed 1.0
    }

    /**
     * Get similarity level description based on score
     */
    private function getSimilarityLevel(float $similarity): string
    {
        if ($similarity >= 0.8) {
            return 'very_high';
        } elseif ($similarity >= 0.6) {
            return 'high';
        } elseif ($similarity >= 0.4) {
            return 'medium';
        } elseif ($similarity >= 0.25) {
            return 'low';
        } else {
            return 'very_low';
        }
    }

    /**
     * Generate recommendation based on similarity and confidence
     */
    private function generateRecommendation(float $similarity, float $realConfidence): string
    {
        if ($similarity >= 0.8 && $realConfidence > 0.8) {
            return 'هذا الخبر مطابق تماماً لخبر موثق في قاعدة بياناتنا بدرجة عالية من الموثوقية';
        } elseif ($similarity >= 0.6 && $realConfidence > 0.8) {
            return 'هذا الخبر مشابه جداً لخبر موثق في قاعدة بياناتنا بدرجة عالية من الموثوقية';
        } elseif ($similarity >= 0.4 && $realConfidence > 0.7) {
            return 'هذا الخبر مشابه لخبر موثق في قاعدة بياناتنا';
        } elseif ($similarity >= 0.25) {
            return 'وُجد خبر مشابه - يُرجى التحقق من المصادر الرسمية';
        } else {
            return 'يُرجى التحقق من المصادر الرسمية';
        }
    }
}
