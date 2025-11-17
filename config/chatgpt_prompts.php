<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ChatGPT Verification Prompts
    |--------------------------------------------------------------------------
    |
    | Pre-defined prompts for fake news verification using ChatGPT
    | as a fallback when database matching fails.
    |
    */

    /*
     * System prompt - Sets the AI's role and behavior
     */
    'system_prompt' => 'You are a news verification expert specializing in detecting misinformation and fake news.

Your role: Analyze news content using your training data knowledge to determine credibility and authenticity.

Respond ONLY with valid JSON in the exact format requested. No additional text before or after the JSON.',

    /*
     * Arabic verification prompt
     */
    'arabic_verification' => 'Analyze this Arabic news text and determine if it contains false or misleading information:

**News Text:**
"{text}"

{trusted_sources_instruction}

Provide your analysis in the following JSON format EXACTLY (no additional text):

{
  "is_potentially_fake": true/false,
  "confidence_score": 0.0-1.0,
  "credibility_level": "high/medium/low/very_low",
  "analysis": {
    "ar": "تحليل مفصل بالعربية يشرح الأسباب",
    "en": "Detailed analysis in English explaining the reasons"
  },
  "warning_signs": [
    {
      "ar": "علامة التحذير بالعربية",
      "en": "Warning sign in English"
    }
  ],
  "recommendation": {
    "ar": "التوصية بالعربية",
    "en": "Recommendation in English"
  },
  "verification_tips": [
    {
      "ar": "نصيحة للتحقق بالعربية",
      "en": "Verification tip in English"
    }
  ],
  "related_topics": ["موضوع 1", "موضوع 2"],
  "fact_check_sources": ["مصدر موثوق 1", "مصدر موثوق 2"]
}',

    /*
     * English verification prompt
     */
    'english_verification' => 'Analyze the following news text carefully and determine if it contains misleading or false information:

"{text}"

{trusted_sources_instruction}

Provide your analysis in the following JSON format exactly (without any additional text before or after):
{
  "is_potentially_fake": true/false,
  "confidence_score": 0.0-1.0,
  "credibility_level": "high/medium/low/very_low",
  "analysis": {
    "en": "Detailed analysis in English explaining the reasons",
    "ar": "تحليل مفصل بالعربية يشرح الأسباب"
  },
  "warning_signs": [
    {
      "en": "Warning sign in English",
      "ar": "علامة التحذير بالعربية"
    }
  ],
  "recommendation": {
    "en": "Recommendation in English",
    "ar": "التوصية بالعربية"
  },
  "verification_tips": [
    {
      "en": "Verification tip in English",
      "ar": "نصيحة للتحقق بالعربية"
    }
  ],
  "related_topics": ["Topic 1", "Topic 2"],
  "fact_check_sources": ["Reliable source 1", "Reliable source 2"]
}',

    // NOTE: Category-specific prompts removed to avoid format confusion
    // The main arabic_verification and english_verification prompts handle all categories

    /*
     * Response format instructions
     */
    'response_format' => [
        'type' => 'json_object',
        'schema' => [
            'is_potentially_fake' => 'boolean',
            'confidence_score' => 'float (0.0-1.0)',
            'credibility_level' => 'string (high/medium/low/very_low)',
            'analysis' => [
                'ar' => 'string (Arabic detailed analysis)',
                'en' => 'string (English detailed analysis)',
            ],
            'warning_signs' => [
                [
                    'ar' => 'string',
                    'en' => 'string',
                ],
            ],
            'recommendation' => [
                'ar' => 'string',
                'en' => 'string',
            ],
            'verification_tips' => [
                [
                    'ar' => 'string',
                    'en' => 'string',
                ],
            ],
            'related_topics' => 'array of strings',
            'fact_check_sources' => 'array of strings',
        ],
    ],

    /*
     * Prompt selection keywords
     * Used to automatically select appropriate prompt based on content
     */
    'keywords' => [
        'legal' => [
            'ar' => ['قانون', 'محكمة', 'قضاء', 'تشريع', 'مرسوم', 'قرار', 'نظام', 'لائحة', 'عقوبة', 'جريمة'],
            'en' => ['law', 'court', 'legal', 'legislation', 'decree', 'regulation', 'statute', 'penalty', 'crime'],
        ],
        'health' => [
            'ar' => ['صحة', 'طب', 'مرض', 'علاج', 'دواء', 'لقاح', 'وباء', 'فيروس', 'طبيب', 'مستشفى'],
            'en' => ['health', 'medical', 'disease', 'treatment', 'medicine', 'vaccine', 'epidemic', 'virus', 'doctor', 'hospital'],
        ],
        'financial' => [
            'ar' => ['اقتصاد', 'مال', 'بنك', 'استثمار', 'أسهم', 'عملة', 'تضخم', 'ميزانية', 'سوق'],
            'en' => ['economy', 'finance', 'bank', 'investment', 'stocks', 'currency', 'inflation', 'budget', 'market'],
        ],
        'social_political' => [
            'ar' => ['سياسة', 'حكومة', 'انتخابات', 'حزب', 'برلمان', 'رئيس', 'وزير', 'مظاهرات'],
            'en' => ['politics', 'government', 'elections', 'party', 'parliament', 'president', 'minister', 'protest'],
        ],
        'tech_science' => [
            'ar' => ['تكنولوجيا', 'علم', 'ذكاء اصطناعي', 'روبوت', 'فضاء', 'كمبيوتر', 'اختراع', 'بحث علمي'],
            'en' => ['technology', 'science', 'artificial intelligence', 'robot', 'space', 'computer', 'invention', 'research'],
        ],
    ],

    /*
     * Fallback threshold
     * When similarity score from database is below this, use ChatGPT
     */
    'fallback_threshold' => 0.70,

    /*
     * Minimum text length for ChatGPT analysis
     */
    'min_text_length' => 50,

    /*
     * Maximum text length for ChatGPT analysis
     */
    'max_text_length' => 5000,

    /*
     * Trusted sources instruction template
     * This will be populated with actual sources from database
     */
    'trusted_sources_template' => [
        'ar' => 'يرجى التحقق من هذا الخبر من خلال البحث في المصادر الموثوقة التالية إذا أمكن:

{sources_list}

ملاحظة: هذه المصادر مُصنفة كموثوقة بناءً على تقييمات المصداقية. إذا وجدت معلومات متضاربة أو مؤكدة في هذه المصادر، اذكر ذلك في تحليلك.',

        'en' => 'Please verify this news by checking the following trusted sources if possible:

{sources_list}

Note: These sources are classified as reliable based on credibility ratings. If you find conflicting or confirming information in these sources, mention it in your analysis.',
    ],

];
