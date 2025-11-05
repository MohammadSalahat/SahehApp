<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
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
     * Initialize controller with Python AI service
     */
    public function __construct(PythonAIService $pythonAI)
    {
        $this->pythonAI = $pythonAI;
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
            'content.required' => 'الرجاء إدخال نص الخبر',
            'content.min' => 'النص قصير جداً. الحد الأدنى 20 حرف',
            'content.max' => 'النص طويل جداً. الحد الأقصى 10000 حرف',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $content = $request->input('content');

        try {
            Log::info('Starting AI-powered verification', [
                'text_length' => strlen($content),
                'word_count' => str_word_count($content),
            ]);

            // DEADLOCK FIX: Fetch fake news candidates HERE in Laravel
            // This prevents Python from calling back to Laravel API
            $candidates = \App\Models\DatasetFakeNews::query()
                ->select('id', 'title', 'content', 'confidence_score', 'origin_dataset_name')
                ->orderBy('confidence_score', 'desc')
                ->limit(100)  // Get top 100 high-confidence entries
                ->get()
                ->toArray();

            Log::info('Fetched fake news candidates', [
                'count' => count($candidates),
            ]);

            // Call Python AI service with BOTH text and candidates
            // Python will process locally without calling Laravel
            $aiResult = $this->pythonAI->verifyArabicNewsWithCandidates(
                text: $content,
                candidates: $candidates,
                threshold: 0.6,  // 60% similarity threshold
                topK: 5          // Return top 5 similar news
            );

            Log::info('AI verification completed', [
                'is_potentially_fake' => $aiResult['is_potentially_fake'],
                'similar_news_found' => $aiResult['similar_news_found'],
                'highest_similarity' => $aiResult['highest_similarity'],
            ]);

            // Prepare data for view
            $viewData = [
                'search_content' => $content,
                'ai_result' => $aiResult,
                'found' => $aiResult['similar_news_found'] > 0,
                'is_potentially_fake' => $aiResult['is_potentially_fake'],
                'similar_news' => $aiResult['similar_news'] ?? [],
                'highest_similarity' => $aiResult['highest_similarity'],
                'recommendation' => $aiResult['recommendation'],
                'query_quality' => $aiResult['query_quality'] ?? [],
                'preprocessed_text' => $aiResult['query_text_preprocessed'] ?? '',
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
                'error_message' => 'عذراً، حدث خطأ في نظام التحقق الذكي. الرجاء المحاولة مرة أخرى لاحقاً.',
                'error_details' => config('app.debug') ? $e->getMessage() : null,
            ]);
        }
    }
}
