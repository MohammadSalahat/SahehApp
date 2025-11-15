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
    'system_prompt' => 'أنت خبير متخصص في التحقق من الأخبار ومكافحة المعلومات المضللة. مهمتك: تحليل النصوص الإخبارية وتحديد صحتها من خلال المقارنة الدلالية العميقة.

منهجية التحقق:

1. فهم السياق: اقرأ الخبر المقدم بعناية وافهم موضوعه الرئيسي والتفاصيل الأساسية

2. التحليل الدلالي (Semantic Comparison):
   - إذا تم تقديم محتوى من مصادر موثوقة، قارن المعنى والمضمون - ليس الكلمات الحرفية
   - ركز على: الموضوع الأساسي، الأحداث المذكورة، الشخصيات، التواريخ، الأماكن
   - تجاهل اختلافات الصياغة اللغوية والأسلوب

3. معايير التطابق:
   الخبر صحيح إذا:
   - الموضوع الرئيسي متطابق (نفس الحدث)
   - المعلومات الأساسية متوافقة (من؟ ماذا؟ متى؟ أين؟)
   - لا توجد تناقضات جوهرية

   الخبر مزيف إذا:
   - الموضوع مختلف تماماً
   - معلومات جوهرية متناقضة أو مبالغ فيها
   - ادعاءات غير موجودة في المصدر

4. نسبة التطابق المقدمة (مثل 60%): هي تطابق كلمات فقط - ليست دلالية
   - نسبة منخفضة قد تكون خبر صحيح بصياغة مختلفة
   - دورك: تحليل المحتوى الفعلي وتحديد التطابق الدلالي

5. القرار: إذا وجدت تطابق دلالي قوي مع مصدر موثوق = الخبر صحيح

قدم إجاباتك بتنسيق JSON محدد دون أي نص إضافي.',

    /*
     * Arabic verification prompt
     */
    'arabic_verification' => 'قم بتحليل النص الإخباري التالي وتحديد صحته من خلال المقارنة الدلالية العميقة:

**الخبر المُقدم للتحقق:**
"{text}"

{trusted_sources_instruction}

**مهمتك:**
1. إذا تم تقديم محتوى من مصادر موثوقة أعلاه، قارنه دلالياً مع الخبر المُقدم
2. ركز على المعنى والحقائق الأساسية، ليس الكلمات الحرفية
3. اسأل نفسك: هل يتحدث الخبران عن نفس الموضوع/الحدث؟
4. إذا كان هناك تطابق دلالي قوي (نفس الموضوع + نفس الحقائق الأساسية) = خبر صحيح
5. إذا لم يكن هناك محتوى من مصادر موثوقة، احكم بناءً على مصداقية الخبر وعلامات التضليل

قدم تحليلك بتنسيق JSON التالي بالضبط (بدون أي نص إضافي قبله أو بعده):
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
  "fact_check_sources": ["مصدر موثوق 1", "مصدر موثوق 2"],
  "sources_checked": ["{checked_sources}"],
  "source_verification_status": {
    "checked_trusted_sources": true/false,
    "found_in_sources": true/false,
    "matching_sources": ["source_name1", "source_name2"],
    "conflicting_information": true/false
  }
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
  "fact_check_sources": ["Reliable source 1", "Reliable source 2"],
  "sources_checked": ["{checked_sources}"],
  "source_verification_status": {
    "checked_trusted_sources": true/false,
    "found_in_sources": true/false,
    "matching_sources": ["source_name1", "source_name2"],
    "conflicting_information": true/false
  }
}',

    /*
     * Legal/Governmental news specific prompt (Arabic)
     */
    'legal_arabic_verification' => 'هذا نص إخباري يتعلق بالقوانين والأنظمة والقرارات الحكومية. قم بتحليله بدقة خاصة للتحقق من:

1. هل يحتوي على ادعاءات قانونية غير صحيحة؟
2. هل المصادر المذكورة حقيقية ورسمية؟
3. هل التواريخ والمعلومات القانونية دقيقة؟
4. هل هناك أي مبالغات أو تضليل؟

النص:
"{text}"

قدم تحليلك بتنسيق JSON المحدد أعلاه مع التركيز على الجوانب القانونية.',

    /*
     * Health-related news prompt
     */
    'health_verification' => 'هذا نص إخباري يتعلق بالصحة والطب. قم بتحليله بعناية خاصة للتحقق من:

1. الادعاءات الطبية والعلمية
2. المبالغات في فعالية العلاجات
3. الأخبار المضللة عن الأمراض والأوبئة
4. المعلومات الصحية الخاطئة

النص:
"{text}"

قدم تحليلك بتنسيق JSON المحدد مع التركيز على المصداقية العلمية.',

    /*
     * Financial/Economic news prompt
     */
    'financial_verification' => 'هذا نص إخباري يتعلق بالاقتصاد والمال. قم بتحليله للتحقق من:

1. الأرقام والإحصائيات الاقتصادية
2. الادعاءات المالية المبالغ فيها
3. مخططات الاحتيال المالي
4. المعلومات الاقتصادية المضللة

النص:
"{text}"

قدم تحليلك بتنسيق JSON المحدد مع التركيز على الدقة المالية.',

    /*
     * Social/Political news prompt
     */
    'social_political_verification' => 'هذا نص إخباري يتعلق بالشؤون الاجتماعية والسياسية. قم بتحليله للتحقق من:

1. التحيز السياسي أو الطائفي
2. الأخبار الكاذبة عن الأحداث الاجتماعية
3. الشائعات والمعلومات غير المؤكدة
4. التلاعب بالحقائق لأغراض سياسية

النص:
"{text}"

قدم تحليلك بتنسيق JSON المحدد مع التركيز على الموضوعية.',

    /*
     * Technology/Science news prompt
     */
    'tech_science_verification' => 'هذا نص إخباري يتعلق بالتكنولوجيا والعلوم. قم بتحليله للتحقق من:

1. الادعاءات العلمية والتقنية
2. الأخبار المبالغ فيها عن الاختراعات
3. المعلومات العلمية الخاطئة
4. الشائعات التقنية

النص:
"{text}"

قدم تحليلك بتنسيق JSON المحدد مع التركيز على الدقة العلمية.',

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
