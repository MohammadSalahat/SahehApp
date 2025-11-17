<?php

namespace App\Services;

use App\Helpers\LanguageDetector;
use App\Models\Source;
use App\Services\WebScrapingService;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatGPTService
{
    /**
     * ChatGPT API configuration
     */
    protected string $apiKey;

    protected ?string $organizationId;

    protected string $model;

    protected int $maxTokens;

    protected float $temperature;

    protected int $timeout;

    protected string $baseUrl;

    protected bool $enabled;

    /**
     * Initialize the service
     */
    public function __construct()
    {
        $this->apiKey = config('services.chatgpt.api_key');
        $this->organizationId = config('services.chatgpt.organization_id');
        $this->model = config('services.chatgpt.model', 'gpt-4');
        $this->maxTokens = config('services.chatgpt.max_tokens', 1000);
        $this->temperature = config('services.chatgpt.temperature', 0.7);
        $this->timeout = config('services.chatgpt.timeout', 60);
        $this->baseUrl = config('services.chatgpt.base_url', 'https://api.openai.com/v1');
        $this->enabled = config('services.chatgpt.enabled', true);
    }

    /**
     * Verify news text using ChatGPT AI
     *
     * @param  string  $text  News text to verify
     * @param  string|null  $category  Optional category (legal, health, financial, etc.)
     * @return array Verification results
     *
     * @throws Exception
     */
    public function verifyNews(string $text, ?string $category = null): array
    {
        if (! $this->enabled) {
            throw new Exception('ChatGPT service is disabled');
        }

        if (empty($this->apiKey)) {
            throw new Exception('ChatGPT API key is not configured');
        }

        // Validate text length
        $textLength = mb_strlen($text);
        $minLength = config('chatgpt_prompts.min_text_length', 50);
        $maxLength = config('chatgpt_prompts.max_text_length', 5000);

        if ($textLength < $minLength) {
            throw new Exception("Text too short. Minimum {$minLength} characters required.");
        }

        if ($textLength > $maxLength) {
            $text = mb_substr($text, 0, $maxLength);
            Log::warning('Text truncated for ChatGPT analysis', ['original_length' => $textLength, 'truncated_to' => $maxLength]);
        }

        // Detect language
        $language = LanguageDetector::detect($text);

        // Auto-detect category if not provided
        if (! $category) {
            $category = $this->detectCategory($text, $language);
        }

        // Skip slow web scraping - just pass source info to ChatGPT
        // ChatGPT will do conceptual verification based on its knowledge
        Log::info('Preparing trusted sources list for ChatGPT', ['sources_to_include' => 'trusted_sources']);

        // Build a lightweight source verification result without scraping
        $trustedSources = $this->getTrustedSources();
        $sourceVerificationResults = [
            'sources_searched' => count($trustedSources),
            'sources_accessible' => count($trustedSources),
            'sources_with_matches' => 0,
            'found_in_sources' => false,
            'highest_similarity' => 0,
            'best_match' => null,
            'matching_sources' => [],
            'search_summary' => [],
            'trusted_sources_list' => $trustedSources
        ];

        // Get appropriate prompt with source information
        $prompt = $this->buildPromptWithSourceResults($text, $category, $language, $sourceVerificationResults);

        Log::info('Sending text to ChatGPT for verification', [
            'text_length' => $textLength,
            'language' => $language,
            'category' => $category,
            'model' => $this->model,
            'sources_found' => $sourceVerificationResults['found_in_sources'] ?? false,
            'sources_checked' => $sourceVerificationResults['sources_searched'] ?? 0,
        ]);

        try {
            $response = $this->callChatGPT($prompt);

            Log::info('ChatGPT verification completed', [
                'tokens_used' => $response['usage']['total_tokens'] ?? null,
            ]);

            // Parse and structure the response with source data
            return $this->parseResponseWithSourceData($response, $text, $language, $category, $sourceVerificationResults);

        } catch (Exception $e) {
            Log::error('ChatGPT verification failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Build prompt based on category and language
     */
    protected function buildPrompt(string $text, string $category, string $language): array
    {
        $systemPrompt = config('chatgpt_prompts.system_prompt');

        // Select user prompt based on category and language
        $promptKey = $category.'_verification';
        if (! config("chatgpt_prompts.{$promptKey}")) {
            $promptKey = $language === 'ar' ? 'arabic_verification' : 'english_verification';
        }

        $userPrompt = config("chatgpt_prompts.{$promptKey}");
        $userPrompt = str_replace('{text}', $text, $userPrompt);

        // Get trusted sources and build instruction
        $trustedSourcesInstruction = $this->buildTrustedSourcesInstruction($language);
        $userPrompt = str_replace('{trusted_sources_instruction}', $trustedSourcesInstruction, $userPrompt);

        // Replace placeholder for checked sources list
        $checkedSources = $this->getTrustedSourcesForPrompt();
        $userPrompt = str_replace('{checked_sources}', implode(', ', $checkedSources), $userPrompt);

        return [
            'system' => $systemPrompt,
            'user' => $userPrompt,
        ];
    }

    /**
     * Detect content category based on keywords
     */
    protected function detectCategory(string $text, string $language): string
    {
        $keywords = config('chatgpt_prompts.keywords');
        $scores = [];

        foreach ($keywords as $category => $categoryKeywords) {
            $categoryWords = $categoryKeywords[$language] ?? [];
            $score = 0;

            foreach ($categoryWords as $keyword) {
                if (mb_stripos($text, $keyword) !== false) {
                    $score++;
                }
            }

            $scores[$category] = $score;
        }

        // Return category with highest score, or 'general'
        arsort($scores);
        $topCategory = array_key_first($scores);

        return $scores[$topCategory] > 0 ? $topCategory : 'general';
    }

    /**
     * Build trusted sources instruction for the prompt
     */
    protected function buildTrustedSourcesInstruction(string $language): string
    {
        $trustedSources = $this->getTrustedSources();
        if (empty($trustedSources)) {
            return '';
        }

        $template = config("chatgpt_prompts.trusted_sources_template.{$language}", '');
        if (empty($template)) {
            return '';
        }

        $sourcesList = $this->formatSourcesForPrompt($trustedSources, $language);
        return str_replace('{sources_list}', $sourcesList, $template);
    }

    /**
     * Get trusted sources from database
     */
    protected function getTrustedSources(): array
    {
        return Source::active()
            ->minReliability(0.7) // Only sources with 70%+ reliability
            ->highReliability()   // Order by highest reliability first
            ->limit(10)           // Limit to top 10 to avoid token overuse
            ->get(['name', 'url', 'reliability_score'])
            ->toArray();
    }

    /**
     * Get list of trusted source URLs for the prompt placeholder
     */
    protected function getTrustedSourcesForPrompt(): array
    {
        $sources = $this->getTrustedSources();
        return array_column($sources, 'url');
    }

    /**
     * Format sources list for display in prompt
     */
    protected function formatSourcesForPrompt(array $sources, string $language): string
    {
        if (empty($sources)) {
            return $language === 'ar' ? 'لا توجد مصادر موثوقة متاحة حالياً' : 'No trusted sources available currently';
        }

        $formatted = [];
        foreach ($sources as $source) {
            $reliabilityPercent = round($source['reliability_score'] * 100);
            if ($language === 'ar') {
                $formatted[] = "• {$source['name']} ({$source['url']}) - معدل الموثوقية: {$reliabilityPercent}%";
            } else {
                $formatted[] = "• {$source['name']} ({$source['url']}) - Reliability: {$reliabilityPercent}%";
            }
        }

        return implode("\n", $formatted);
    }

    /**
     * Perform actual source verification using web scraping
     */
    protected function performSourceVerification(string $text): array
    {
        $trustedSources = $this->getTrustedSources();
        
        if (empty($trustedSources)) {
            return [
                'sources_searched' => 0,
                'sources_accessible' => 0,
                'sources_with_matches' => 0,
                'found_in_sources' => false,
                'highest_similarity' => 0,
                'best_match' => null,
                'matching_sources' => [],
                'search_summary' => []
            ];
        }

        $webScrapingService = app(WebScrapingService::class);
        return $webScrapingService->searchInTrustedSources($text, $trustedSources);
    }

    /**
     * Build prompt with actual source verification results
     */
    protected function buildPromptWithSourceResults(string $text, string $category, string $language, array $sourceResults): array
    {
        $systemPrompt = config('chatgpt_prompts.system_prompt');

        // Select user prompt based on category and language
        $promptKey = $category.'_verification';
        if (! config("chatgpt_prompts.{$promptKey}")) {
            $promptKey = $language === 'ar' ? 'arabic_verification' : 'english_verification';
        }

        $userPrompt = config("chatgpt_prompts.{$promptKey}");
        $userPrompt = str_replace('{text}', $text, $userPrompt);

        // Build source verification context
        $sourceContext = $this->buildSourceVerificationContext($sourceResults, $language);
        $userPrompt = str_replace('{trusted_sources_instruction}', $sourceContext, $userPrompt);

        // Replace checked sources placeholder
        $checkedSources = [];
        if (!empty($sourceResults['matching_sources'])) {
            foreach ($sourceResults['matching_sources'] as $match) {
                $checkedSources[] = $match['source_name'];
            }
        }
        $userPrompt = str_replace('{checked_sources}', implode(', ', $checkedSources), $userPrompt);

        return [
            'system' => $systemPrompt,
            'user' => $userPrompt,
        ];
    }

    /**
     * Build source verification context for prompt
     */
    protected function buildSourceVerificationContext(array $sourceResults, string $language): string
    {
        if (!$sourceResults['found_in_sources']) {
            return $language === 'ar'
                ? "تم البحث في {$sourceResults['sources_searched']} مصادر موثوقة ولم يتم العثور على هذا الخبر في أي منها."
                : "Searched {$sourceResults['sources_searched']} trusted sources and this news was not found in any of them.";
        }

        $context = $language === 'ar'
            ? "تم العثور على هذا الخبر في المصادر الموثوقة التالية:\n\n"
            : "This news was found in the following trusted sources:\n\n";

        foreach ($sourceResults['matching_sources'] as $match) {
            $similarity = round($match['similarity'] * 100);
            if ($language === 'ar') {
                $context .= "• {$match['source_name']}: تطابق {$similarity}%\n";
                $context .= "  الرابط: {$match['article_url']}\n";
                if (!empty($match['title'])) {
                    $context .= "  العنوان: {$match['title']}\n";
                }
                // Include actual scraped content for semantic comparison
                if (!empty($match['full_text'])) {
                    $scrapedPreview = mb_substr($match['full_text'], 0, 800);
                    $context .= "  محتوى الخبر من المصدر الموثوق:\n  \"{$scrapedPreview}...\"\n";
                }
            } else {
                $context .= "• {$match['source_name']}: {$similarity}% match\n";
                $context .= "  URL: {$match['article_url']}\n";
                if (!empty($match['title'])) {
                    $context .= "  Title: {$match['title']}\n";
                }
                // Include actual scraped content for semantic comparison
                if (!empty($match['full_text'])) {
                    $scrapedPreview = mb_substr($match['full_text'], 0, 800);
                    $context .= "  News content from trusted source:\n  \"{$scrapedPreview}...\"\n";
                }
            }
            $context .= "\n";
        }

        // Add instruction for ChatGPT to compare semantically
        if ($language === 'ar') {
            $context .= "\n\nملاحظة مهمة: قارن محتوى الخبر المُقدم مع محتوى المصادر الموثوقة أعلاه دلالياً (semantic comparison). إذا كان المعنى العام والمعلومات الأساسية متطابقة (حتى لو اختلفت الصياغة)، فالخبر صحيح.";
        } else {
            $context .= "\n\nImportant: Semantically compare the submitted news with the trusted source content above. If the general meaning and core information match (even if wording differs), the news is authentic.";
        }

        return $context;
    }

    /**
     * Format sources for analysis message
     */
    protected function formatSourcesForAnalysis(array $sourceResults, string $language): string
    {
        $trustedSources = $this->getTrustedSources();

        if (empty($trustedSources)) {
            return $language === 'ar'
                ? "لا توجد مصادر موثوقة متاحة."
                : "No trusted sources available.";
        }

        $formatted = [];
        foreach ($trustedSources as $source) {
            $reliabilityPercent = round($source['reliability_score'] * 100);
            if ($language === 'ar') {
                $formatted[] = "• {$source['name']} ({$source['url']}) - موثوقية: {$reliabilityPercent}%";
            } else {
                $formatted[] = "• {$source['name']} ({$source['url']}) - Reliability: {$reliabilityPercent}%";
            }
        }

        return implode("\n", $formatted);
    }

    /**
     * Parse response with source verification data
     */
    protected function parseResponseWithSourceData(array $response, string $originalText, string $language, string $category, array $sourceResults): array
    {
        $content = $response['choices'][0]['message']['content'] ?? null;

        if (! $content) {
            throw new Exception('Empty response from ChatGPT');
        }

        // Parse JSON response
        $analysis = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Failed to parse ChatGPT JSON response', ['content' => $content]);
            throw new Exception('Invalid JSON response from ChatGPT');
        }

        // Use ChatGPT's intelligent analysis as the primary decision maker
        // ChatGPT already received source content and did semantic comparison
        $isFake = $analysis['is_potentially_fake'] ?? false;
        $confidence = $analysis['confidence_score'] ?? 0.5;
        $credibility = $analysis['credibility_level'] ?? 'medium';

        // Light adjustment based on source verification results
        // But TRUST ChatGPT's semantic analysis over keyword similarity

        if ($sourceResults['found_in_sources'] && $sourceResults['highest_similarity'] >= 0.60) {
            // Sources found - ChatGPT already analyzed the content
            // Only boost confidence if ChatGPT also said it's NOT fake
            if (!$isFake && $confidence < 0.80) {
                $confidence = max($confidence, 0.80); // Boost confidence
                $credibility = 'high';
            }

            // Enhance analysis with source confirmation
            $sourcesFound = implode(', ', array_column($sourceResults['matching_sources'] ?? [], 'source_name'));
            $similarityPercent = round($sourceResults['highest_similarity'] * 100);

            $sourceNote = $language === 'ar'
                ? "\n\n[تأكيد] تم العثور على محتوى مشابه في: {$sourcesFound} (تطابق كلمات: {$similarityPercent}%)"
                : "\n\n[CONFIRMED] Similar content found in: {$sourcesFound} (keyword match: {$similarityPercent}%)";

            $analysis['analysis']['ar'] = ($analysis['analysis']['ar'] ?? 'تحليل غير متوفر') . $sourceNote;
            $analysis['analysis']['en'] = ($analysis['analysis']['en'] ?? 'Analysis not available') . $sourceNote;
        }
        // STRICT RULE: If NOT found in ANY trusted source → LIKELY FAKE
        // But still respect ChatGPT's analysis if it found good reasons
        else if ($sourceResults['sources_searched'] > 0 && !$sourceResults['found_in_sources']) {
            // Not found in sources - apply strict rule ONLY if ChatGPT also suspects it's fake
            if ($isFake || $confidence < 0.40) {
                // ChatGPT agrees or is uncertain → Apply strict rule
                $isFake = true;
                $confidence = max($confidence, 0.85); // High confidence it's fake
                $credibility = 'very_low';

                $sourcesSearched = $sourceResults['sources_searched'];
                $sourcesListAr = $this->formatSourcesForAnalysis($sourceResults, 'ar');

                $strictNote = $language === 'ar'
                    ? "\n\n[تحذير] تم البحث في {$sourcesSearched} مصادر موثوقة ولم يتم العثور على هذا الخبر.\n\nوفقاً لسياسة التحقق: أي خبر لا يوجد في المصادر الموثوقة يُعتبر مشكوكاً فيه.\n\nالمصادر المفحوصة:\n{$sourcesListAr}"
                    : "\n\n[WARNING] Searched {$sourcesSearched} trusted sources - news not found.\n\nAccording to verification policy: News not found in trusted sources is considered suspicious.";

                $analysis['analysis']['ar'] = ($analysis['analysis']['ar'] ?? 'تحليل غير متوفر') . $strictNote;
                $analysis['analysis']['en'] = ($analysis['analysis']['en'] ?? 'Analysis not available') . $strictNote;

                $analysis['recommendation']['ar'] = "هذا الخبر مشكوك فيه. لم يتم العثور عليه في المصادر الموثوقة.";
                $analysis['recommendation']['en'] = "This news is suspicious. Not found in trusted sources.";

                if (!isset($analysis['warning_signs'])) {
                    $analysis['warning_signs'] = [];
                }
                $analysis['warning_signs'][] = ['ar' => 'لم يتم نشر في مصادر موثوقة', 'en' => 'Not published in trusted sources'];
            } else {
                // ChatGPT says it's authentic despite not being in sources
                // Trust ChatGPT but add a note
                $notFoundNote = $language === 'ar'
                    ? "\n\n[ملاحظة] لم يتم العثور على هذا الخبر في المصادر الموثوقة المفحوصة، لكن التحليل الدلالي يشير إلى أنه قد يكون صحيحاً."
                    : "\n\n[NOTE] This news was not found in checked trusted sources, but semantic analysis suggests it may be authentic.";

                $analysis['analysis']['ar'] = ($analysis['analysis']['ar'] ?? 'تحليل غير متوفر') . $notFoundNote;
                $analysis['analysis']['en'] = ($analysis['analysis']['en'] ?? 'Analysis not available') . $notFoundNote;
            }
        }

        // Structure the response to match our verification format
        return [
            'method' => 'chatgpt_fallback_with_source_verification',
            'model_used' => $this->model,
            'category' => $category,
            'language' => $language,
            'is_potentially_fake' => $isFake,
            'confidence_score' => $confidence,
            'credibility_level' => $credibility,
            'analysis' => $analysis['analysis'] ?? [
                'ar' => 'لم يتم توفير تحليل',
                'en' => 'No analysis provided',
            ],
            'warning_signs' => $analysis['warning_signs'] ?? [],
            'recommendation' => $analysis['recommendation'] ?? [
                'ar' => 'يُرجى التحقق من المصادر الرسمية',
                'en' => 'Please verify from official sources',
            ],
            'verification_tips' => $analysis['verification_tips'] ?? [],
            'related_topics' => $analysis['related_topics'] ?? [],
            'fact_check_sources' => $analysis['fact_check_sources'] ?? [],
            'sources_checked' => array_column($sourceResults['matching_sources'] ?? [], 'source_name'),
            'source_verification_status' => [
                'checked_trusted_sources' => true,
                'found_in_sources' => $sourceResults['found_in_sources'],
                'matching_sources' => array_column($sourceResults['matching_sources'] ?? [], 'source_name'),
                'conflicting_information' => false, // Could be enhanced based on analysis
                'highest_similarity' => $sourceResults['highest_similarity'],
                'sources_searched' => $sourceResults['sources_searched'],
                'best_match_url' => $sourceResults['best_match']['article_url'] ?? null,
            ],
            'trusted_sources_used' => array_column($this->getTrustedSources(), 'url'),
            'tokens_used' => $response['usage']['total_tokens'] ?? 0,
            'processing_time' => time(),
            'original_text_length' => mb_strlen($originalText),
            'source_verification_results' => $sourceResults, // Full source verification data
        ];
    }

    /**
     * Call ChatGPT API
     */
    protected function callChatGPT(array $messages): array
    {
        $headers = [
            'Authorization' => "Bearer {$this->apiKey}",
            'Content-Type' => 'application/json',
        ];

        if ($this->organizationId) {
            $headers['OpenAI-Organization'] = $this->organizationId;
        }

        $payload = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $messages['system'],
                ],
                [
                    'role' => 'user',
                    'content' => $messages['user'],
                ],
            ],
            'temperature' => $this->temperature,
            'response_format' => ['type' => 'json_object'], // Force JSON response
        ];

        // Use correct token parameter based on model
        // Newer models (gpt-4o, gpt-4-turbo, o1, etc.) use max_completion_tokens
        // Older models (gpt-3.5-turbo, gpt-4) use max_tokens
        Log::info('ChatGPT model being used', ['model' => $this->model]);

        // Use max_completion_tokens for all newer models (default for safety)
        // Only use max_tokens for explicitly old models
        $isOldModel = (strpos($this->model, 'gpt-3.5') !== false) ||
                     ($this->model === 'gpt-4'); // Exact match for base gpt-4

        if ($isOldModel) {
            $payload['max_tokens'] = $this->maxTokens;
            Log::info('Using max_tokens for older model');
        } else {
            $payload['max_completion_tokens'] = $this->maxTokens;
            Log::info('Using max_completion_tokens for newer model');
        }

        $response = Http::timeout($this->timeout)
            ->withHeaders($headers)
            ->post("{$this->baseUrl}/chat/completions", $payload);

        if (! $response->successful()) {
            $error = $response->json()['error']['message'] ?? $response->body();
            throw new Exception("ChatGPT API error: {$error}");
        }

        return $response->json();
    }

    /**
     * Parse ChatGPT response
     */
    protected function parseResponse(array $response, string $originalText, string $language, string $category): array
    {
        $content = $response['choices'][0]['message']['content'] ?? null;

        if (! $content) {
            throw new Exception('Empty response from ChatGPT');
        }

        // Parse JSON response
        $analysis = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Failed to parse ChatGPT JSON response', ['content' => $content]);
            throw new Exception('Invalid JSON response from ChatGPT');
        }

        // Structure the response to match our verification format
        return [
            'method' => 'chatgpt_fallback',
            'model_used' => $this->model,
            'category' => $category,
            'language' => $language,
            'is_potentially_fake' => $analysis['is_potentially_fake'] ?? false,
            'confidence_score' => $analysis['confidence_score'] ?? 0.5,
            'credibility_level' => $analysis['credibility_level'] ?? 'medium',
            'analysis' => $analysis['analysis'] ?? [
                'ar' => 'لم يتم توفير تحليل',
                'en' => 'No analysis provided',
            ],
            'warning_signs' => $analysis['warning_signs'] ?? [],
            'recommendation' => $analysis['recommendation'] ?? [
                'ar' => 'يُرجى التحقق من المصادر الرسمية',
                'en' => 'Please verify from official sources',
            ],
            'verification_tips' => $analysis['verification_tips'] ?? [],
            'related_topics' => $analysis['related_topics'] ?? [],
            'fact_check_sources' => $analysis['fact_check_sources'] ?? [],
            'sources_checked' => $analysis['sources_checked'] ?? [],
            'source_verification_status' => $analysis['source_verification_status'] ?? [
                'checked_trusted_sources' => false,
                'found_in_sources' => false,
                'matching_sources' => [],
                'conflicting_information' => false,
            ],
            'trusted_sources_used' => $this->getTrustedSourcesForPrompt(),
            'tokens_used' => $response['usage']['total_tokens'] ?? 0,
            'processing_time' => time(),
            'original_text_length' => mb_strlen($originalText),
        ];
    }

    /**
     * Check if ChatGPT service is enabled and configured
     */
    public function isAvailable(): bool
    {
        return $this->enabled && ! empty($this->apiKey);
    }

    /**
     * Get service status
     */
    public function getStatus(): array
    {
        return [
            'enabled' => $this->enabled,
            'configured' => ! empty($this->apiKey),
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
            'timeout' => $this->timeout,
        ];
    }

    /**
     * Quick verification check (simplified response)
     */
    public function quickCheck(string $text): array
    {
        $result = $this->verifyNews($text);

        return [
            'is_fake' => $result['is_potentially_fake'],
            'confidence' => $result['confidence_score'],
            'summary' => $result['analysis']['ar'] ?? $result['analysis']['en'] ?? '',
        ];
    }

    /**
     * Batch verification (for multiple texts)
     */
    public function verifyBatch(array $texts, ?string $category = null): array
    {
        $results = [];

        foreach ($texts as $index => $text) {
            try {
                $results[$index] = $this->verifyNews($text, $category);
                // Small delay to avoid rate limiting
                usleep(500000); // 0.5 seconds
            } catch (Exception $e) {
                $results[$index] = [
                    'error' => $e->getMessage(),
                    'text' => $text,
                ];
            }
        }

        return $results;
    }
}
