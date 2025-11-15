# ChatGPT Fallback System - Complete Documentation

## 🎯 Overview

The ChatGPT Fallback System provides intelligent AI-powered verification when no matches are found in the database. It uses OpenAI's GPT models to analyze news content and provide detailed credibility assessments.

---

## 🏗️ Architecture

### Flow Diagram

```
User submits text
     ↓
Primary Verification (AraBERT/FULLTEXT)
     ↓
Similarity Found? ────YES──→ Return database matches
     ↓ NO
Check Fallback Threshold (<70%)
     ↓
ChatGPT Available? ────NO──→ Return "No matches found"
     ↓ YES
Send to ChatGPT API
     ↓
Receive AI Analysis
     ↓
Log to Database
     ↓
Return Combined Results
```

---

## 📁 Files Structure

### Created Files

```
config/
├── services.php                          # ChatGPT API configuration
└── chatgpt_prompts.php                   # Pre-defined prompts

app/
├── Services/
│   └── ChatGPTService.php                # Main ChatGPT service class
├── Models/
│   └── ChatGPTVerification.php           # Database model
└── Http/Controllers/Web/
    └── VerificationController.php        # Updated with fallback logic

database/migrations/
└── 2025_11_10_145150_create_chatgpt_verifications_table.php
```

---

## 🔧 Configuration

### Environment Variables

Add these to your `.env` file:

```env
# ChatGPT/OpenAI Configuration
CHATGPT_API_KEY=sk-proj-xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
CHATGPT_ORGANIZATION_ID=org-xxxxxxxxxxxxx              # Optional
CHATGPT_MODEL=gpt-4                                    # or gpt-3.5-turbo
CHATGPT_MAX_TOKENS=1000
CHATGPT_TEMPERATURE=0.7
CHATGPT_TIMEOUT=60
CHATGPT_BASE_URL=https://api.openai.com/v1
CHATGPT_ENABLED=true
```

### Getting OpenAI API Key

1. Go to https://platform.openai.com/api-keys
2. Sign up or log in
3. Click "Create new secret key"
4. Copy the key and add to `.env`
5. **Important**: Keep your API key secure!

---

## 🎨 Pre-defined Prompts

Located in `config/chatgpt_prompts.php`, the system includes:

### 1. **General Verification Prompts**
- `arabic_verification`: For Arabic news
- `english_verification`: For English news

### 2. **Category-Specific Prompts**
- `legal_arabic_verification`: Legal/governmental news
- `health_verification`: Health and medical news
- `financial_verification`: Economic/financial news
- `social_political_verification`: Social/political news
- `tech_science_verification`: Technology/science news

### 3. **Auto-Detection Keywords**

The system automatically detects content category based on keywords:

```php
'legal' => ['قانون', 'محكمة', 'قضاء', 'law', 'court', 'legal']
'health' => ['صحة', 'طب', 'مرض', 'health', 'medical', 'disease']
'financial' => ['اقتصاد', 'مال', 'بنك', 'economy', 'finance', 'bank']
// ... and more
```

---

## 💻 ChatGPTService Class

### Main Methods

#### 1. `verifyNews(string $text, ?string $category = null): array`

Verify news text using ChatGPT.

```php
use App\Services\ChatGPTService;

$chatGPT = app(ChatGPTService::class);
$result = $chatGPT->verifyNews($newsText);
```

**Returns:**
```php
[
    'method' => 'chatgpt_fallback',
    'model_used' => 'gpt-4',
    'category' => 'legal',
    'language' => 'ar',
    'is_potentially_fake' => true,
    'confidence_score' => 0.85,
    'credibility_level' => 'low',
    'analysis' => [
        'ar' => 'تحليل مفصل بالعربية...',
        'en' => 'Detailed analysis in English...'
    ],
    'warning_signs' => [
        ['ar' => 'علامة تحذير', 'en' => 'Warning sign']
    ],
    'recommendation' => [
        'ar' => 'التوصية', 
        'en' => 'Recommendation'
    ],
    'verification_tips' => [...],
    'related_topics' => ['topic1', 'topic2'],
    'fact_check_sources' => ['source1.com', 'source2.com'],
    'tokens_used' => 450
]
```

#### 2. `isAvailable(): bool`

Check if ChatGPT service is enabled and configured.

```php
if ($chatGPT->isAvailable()) {
    // Use ChatGPT
}
```

#### 3. `quickCheck(string $text): array`

Simplified verification (minimal response).

```php
$result = $chatGPT->quickCheck($text);
// Returns: ['is_fake' => true, 'confidence' => 0.85, 'summary' => '...']
```

#### 4. `verifyBatch(array $texts, ?string $category = null): array`

Verify multiple texts at once.

```php
$results = $chatGPT->verifyBatch([
    'News text 1',
    'News text 2',
    'News text 3'
]);
```

---

## 🗄️ Database Schema

### `chatgpt_verifications` Table

```sql
CREATE TABLE chatgpt_verifications (
    id BIGINT PRIMARY KEY,
    original_text TEXT,
    language VARCHAR(2) DEFAULT 'ar',
    category VARCHAR(255),
    model_used VARCHAR(255) DEFAULT 'gpt-4',
    
    -- Results
    is_potentially_fake BOOLEAN DEFAULT FALSE,
    confidence_score DECIMAL(5,4) DEFAULT 0.5,
    credibility_level VARCHAR(255) DEFAULT 'medium',
    
    -- Analysis (JSON columns)
    analysis JSON,
    warning_signs JSON,
    recommendation JSON,
    verification_tips JSON,
    related_topics JSON,
    fact_check_sources JSON,
    
    -- Meta
    tokens_used INT DEFAULT 0,
    processing_time_ms INT,
    user_ip VARCHAR(45),
    user_id BIGINT,
    status ENUM('pending', 'completed', 'failed'),
    error_message TEXT,
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX(language),
    INDEX(category),
    INDEX(is_potentially_fake),
    INDEX(credibility_level)
);
```

---

## 🔄 Verification Flow

### Primary Methods

1. **Arabic**: AraBERT semantic similarity
2. **English**: FULLTEXT database matching

### Fallback Trigger

ChatGPT is triggered when:

```php
// No matches found
$similar_news_found === 0

// OR similarity below threshold
$highest_similarity < 0.70 (configurable)
```

### Controller Implementation

```php
// In VerificationController@verify()

// Step 1: Try database matching
$aiResult = $this->verifyArabicContent($content);

// Step 2: Check if fallback needed
if ($aiResult['similar_news_found'] === 0) {
    // Step 3: Use ChatGPT
    if ($this->chatGPT->isAvailable()) {
        $chatGPTResult = $this->chatGPT->verifyNews($content);
        
        // Step 4: Log to database
        ChatGPTVerification::create([...]);
        
        $usedFallback = true;
    }
}

// Step 5: Return combined results
return view('verification-result', [
    'ai_result' => $aiResult,
    'chatgpt_result' => $chatGPTResult,
    'used_chatgpt_fallback' => $usedFallback
]);
```

---

## 🎨 Prompt Engineering

### System Prompt

Sets AI's role and behavior:

```
أنت خبير في التحقق من الأخبار والمعلومات المضللة...
```

### User Prompt Structure

```
قم بتحليل النص الإخباري التالي:
"{user_text}"

قدم تحليلك بتنسيق JSON:
{
  "is_potentially_fake": true/false,
  "confidence_score": 0.0-1.0,
  "analysis": {...},
  ...
}
```

### Category-Specific Prompts

Each category has specialized analysis focus:

- **Legal**: Checks legal accuracy, official sources
- **Health**: Validates medical claims, scientific evidence
- **Financial**: Verifies economic data, statistics
- **Political**: Detects bias, checks factual accuracy
- **Tech**: Validates technical claims, scientific basis

---

## 📊 Response Format

### Standard Response

```json
{
  "is_potentially_fake": true,
  "confidence_score": 0.85,
  "credibility_level": "low",
  "analysis": {
    "ar": "التحليل المفصل بالعربية يشرح أن النص يحتوي على...",
    "en": "Detailed analysis explaining that the text contains..."
  },
  "warning_signs": [
    {
      "ar": "لا توجد مصادر رسمية مذكورة",
      "en": "No official sources mentioned"
    },
    {
      "ar": "ادعاءات مبالغ فيها وغير واقعية",
      "en": "Exaggerated and unrealistic claims"
    }
  ],
  "recommendation": {
    "ar": "لا تثق بهذا المحتوى. تحقق من المصادر الرسمية.",
    "en": "Do not trust this content. Verify from official sources."
  },
  "verification_tips": [
    {
      "ar": "ابحث عن الخبر في المواقع الإخبارية الموثوقة",
      "en": "Search for the news on trusted news websites"
    },
    {
      "ar": "تحقق من المصدر الرسمي للجهة المذكورة",
      "en": "Verify from the official source of the mentioned authority"
    }
  ],
  "related_topics": [
    "القوانين والتشريعات",
    "المحكمة العليا",
    "النقل والمواصلات"
  ],
  "fact_check_sources": [
    "موقع المحكمة العليا الرسمي",
    "موقع وزارة النقل",
    "منصة التحقق من المعلومات"
  ]
}
```

---

## 💰 Cost Management

### Token Usage

ChatGPT charges based on tokens used:

- **GPT-4**: $0.03/1K input tokens, $0.06/1K output tokens
- **GPT-3.5-turbo**: $0.001/1K input tokens, $0.002/1K output tokens

### Typical Usage

- Input: ~500-800 tokens (prompt + text)
- Output: ~400-600 tokens (analysis)
- **Total per request**: ~1,000-1,400 tokens

### Cost Estimation

```
GPT-4:
- Per request: ~$0.08-$0.12
- 100 requests: ~$8-$12

GPT-3.5-turbo:
- Per request: ~$0.003-$0.004
- 100 requests: ~$0.30-$0.40
```

### Optimization Tips

1. **Use GPT-3.5-turbo** for cost-effective results
2. **Limit max_tokens** to reduce output cost
3. **Cache frequent queries**
4. **Set daily/monthly budget limits**
5. **Monitor token usage** via database logs

---

## 🔒 Security

### API Key Protection

1. **Never commit** `.env` file
2. **Use environment variables** only
3. **Rotate keys** periodically
4. **Set usage limits** in OpenAI dashboard
5. **Monitor unusual activity**

### Rate Limiting

Implement rate limiting to prevent abuse:

```php
// In VerificationController
use Illuminate\Support\Facades\RateLimiter;

if (RateLimiter::tooManyAttempts('chatgpt:'.$request->ip(), 10)) {
    return back()->withErrors([
        'content' => 'Too many verification attempts. Please try again later.'
    ]);
}

RateLimiter::hit('chatgpt:'.$request->ip(), 60); // 10 per minute
```

---

## 📈 Monitoring

### Database Queries

```php
// Total ChatGPT verifications
ChatGPTVerification::count();

// Potentially fake count
ChatGPTVerification::where('is_potentially_fake', true)->count();

// By language
ChatGPTVerification::where('language', 'ar')->count();

// By category
ChatGPTVerification::where('category', 'legal')->count();

// Total tokens used
ChatGPTVerification::sum('tokens_used');

// Average confidence score
ChatGPTVerification::avg('confidence_score');

// Failed attempts
ChatGPTVerification::where('status', 'failed')->get();
```

### Logs

Check `storage/logs/laravel.log` for:

```
[2025-11-10 14:00:00] INFO: No similarity found in database, using ChatGPT fallback
[2025-11-10 14:00:05] INFO: ChatGPT fallback verification completed
[2025-11-10 14:00:05] INFO: Tokens used: 1250
```

---

## 🧪 Testing

### Test ChatGPT Service

```php
php artisan tinker
```

```php
use App\Services\ChatGPTService;

$chatGPT = app(ChatGPTService::class);

// Check if available
$chatGPT->isAvailable(); // Should return true

// Get status
$chatGPT->getStatus();

// Test verification
$result = $chatGPT->verifyNews('أعلن المحكمة العليا عن قانون جديد...');

print_r($result);
```

### Test Fallback Flow

```bash
# Submit a news text that's NOT in database
curl -X POST http://localhost:8000/verify \
  -d "content=هذا خبر جديد تماماً لا يوجد في قاعدة البيانات"
```

Expected: ChatGPT fallback should be triggered.

---

## 📖 Usage Examples

### Example 1: Arabic Legal News

**Input:**
```
المحكمة العليا أصدرت قراراً بحظر استخدام الهواتف المحمولة في جميع الأماكن العامة
```

**ChatGPT Response:**
```json
{
  "is_potentially_fake": true,
  "confidence_score": 0.90,
  "credibility_level": "very_low",
  "analysis": {
    "ar": "هذا الخبر غير صحيح. لا توجد أي قرارات رسمية من المحكمة العليا بهذا الخصوص...",
    "en": "This news is false. There are no official decisions from the Supreme Court regarding this..."
  }
}
```

### Example 2: English Health News

**Input:**
```
Drinking 10 cups of coffee daily guarantees instant weight loss without exercise
```

**ChatGPT Response:**
```json
{
  "is_potentially_fake": true,
  "confidence_score": 0.95,
  "credibility_level": "very_low",
  "warning_signs": [
    {
      "en": "Unrealistic health claims",
      "ar": "ادعاءات صحية غير واقعية"
    },
    {
      "en": "No scientific evidence provided",
      "ar": "لا توجد أدلة علمية"
    }
  ]
}
```

---

## 🎛️ Customization

### Modify Prompts

Edit `config/chatgpt_prompts.php`:

```php
'arabic_verification' => 'قم بتحليل النص التالي بدقة...',
```

### Change Model

```env
CHATGPT_MODEL=gpt-3.5-turbo  # Cheaper, faster
# or
CHATGPT_MODEL=gpt-4          # More accurate, expensive
```

### Adjust Fallback Threshold

```php
// In config/chatgpt_prompts.php
'fallback_threshold' => 0.60,  // Use ChatGPT if similarity < 60%
```

### Add New Categories

```php
// In config/chatgpt_prompts.php
'sports_verification' => 'هذا نص رياضي. قم بتحليله...',

'keywords' => [
    'sports' => [
        'ar' => ['رياضة', 'كرة', 'مباراة'],
        'en' => ['sports', 'football', 'match']
    ]
]
```

---

## ⚠️ Limitations

1. **API Key Required**: Must have OpenAI account
2. **Cost**: Each request costs money
3. **Rate Limits**: OpenAI has rate limits
4. **Response Time**: Slower than database matching
5. **Accuracy**: Not 100% perfect
6. **Language Support**: Best for English/Arabic

---

## 🚀 Deployment Checklist

- [ ] Add `CHATGPT_API_KEY` to production `.env`
- [ ] Set appropriate `max_tokens` limit
- [ ] Configure rate limiting
- [ ] Set up monitoring/alerts
- [ ] Test fallback flow
- [ ] Review and adjust prompts
- [ ] Set budget limits in OpenAI dashboard
- [ ] Enable error logging
- [ ] Test with real news samples
- [ ] Document for team

---

## 📚 Additional Resources

- [OpenAI API Documentation](https://platform.openai.com/docs)
- [GPT Best Practices](https://platform.openai.com/docs/guides/gpt-best-practices)
- [Prompt Engineering Guide](https://platform.openai.com/docs/guides/prompt-engineering)
- [OpenAI Pricing](https://openai.com/pricing)

---

## 🎉 Summary

The ChatGPT Fallback System provides:

✅ **Intelligent fallback** when database matching fails
✅ **Pre-defined prompts** for different news categories
✅ **Bilingual support** (Arabic & English)
✅ **Comprehensive logging** to database
✅ **Category auto-detection** based on keywords
✅ **Cost optimization** options
✅ **Easy configuration** via environment variables
✅ **Detailed analysis** with verification tips
✅ **Production-ready** with security measures

**Your verification system now has AI-powered backup!** 🚀
