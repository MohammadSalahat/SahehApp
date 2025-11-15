# Arabic Semantic Matching Test Cases

## 📋 Original Fake News (Stored in Database)

**Title**: المحكمة العليا تعلن مراجعة قوانين النقل والمواصلات

**Content**:
```
أعلن المحكمة العليا عن مراجعة قوانين النقل والمواصلات بهدف تحسين الخدمات القضائية وتطوير العدالة في المملكة العربية السعودية. هذا القرار يأتي في إطار رؤية المملكة 2030 لتطوير القطاع القضائي وتعزيز سيادة القانون. سيتم تطبيق هذه التحديثات خلال الأشهر القادمة مع توفير التدريب اللازم للكوادر القضائية. كما سيتم إتاحة معلومات تفصيلية للمواطنين حول هذه التغييرات من خلال المواقع الرسمية والمنصات الإلكترونية. للمزيد من التفاصيل والمعلومات الرسمية، يرجى مراجعة الموقع الإلكتروني الرسمي للجهة المختصة.
```

**Expected Match**: 100% (Exact match)

---

## ✅ Test Case 1: Summarized Version (Short)

**Purpose**: Test if system catches abbreviated versions

**Content**:
```
المحكمة العليا أعلنت عن تحديث قوانين النقل والمواصلات لتحسين الخدمات القضائية في السعودية. القرار جزء من رؤية 2030 لتطوير القطاع القضائي.
```

**Key Features**:
- 70% shorter than original
- Same core message
- Key terms: "المحكمة العليا", "قوانين النقل", "رؤية 2030", "القطاع القضائي"

**Expected Similarity**: 75-85%
**Should Be Caught**: ✅ YES (Above 70% threshold)

---

## ✅ Test Case 2: Paraphrased with Different Words

**Purpose**: Test semantic understanding with synonym replacement

**Content**:
```
قامت أعلى محكمة في البلاد بالإعلان عن مراجعة شاملة للأنظمة المتعلقة بوسائل النقل والمواصلات، وذلك بغرض تطوير الخدمات في الجهاز القضائي وتحسين مستوى العدالة في المملكة. هذه الخطوة تأتي ضمن خطة المملكة للتنمية 2030 التي تستهدف النهوض بالقطاع القضائي وترسيخ حكم القانون.
```

**Key Features**:
- "المحكمة العليا" → "أعلى محكمة في البلاد"
- "قوانين" → "أنظمة"
- "بهدف" → "بغرض"
- "رؤية المملكة" → "خطة المملكة للتنمية"
- "تعزيز سيادة القانون" → "ترسيخ حكم القانون"

**Expected Similarity**: 80-90%
**Should Be Caught**: ✅ YES (Semantic similarity maintained)

---

## ✅ Test Case 3: Reordered Structure with Added Context

**Purpose**: Test if different sentence order is detected

**Content**:
```
في إطار رؤية 2030 للمملكة العربية السعودية، تم الإعلان من قبل المحكمة العليا عن مراجعة القوانين الخاصة بالنقل والمواصلات. الهدف الأساسي من هذه المراجعة هو تطوير العدالة وتحسين الخدمات القضائية المقدمة للمواطنين. ومن المقرر أن يتم تطبيق التحديثات الجديدة خلال الفترة القادمة مع ضمان تدريب العاملين في القطاع القضائي بشكل مناسب.
```

**Key Features**:
- Starts with "رؤية 2030" instead of "المحكمة العليا"
- Reordered information flow
- Same semantic meaning
- Added some connecting phrases

**Expected Similarity**: 75-85%
**Should Be Caught**: ✅ YES (Core content preserved)

---

## ✅ Test Case 4: Very Brief Summary (Minimal Version)

**Purpose**: Test minimum viable match with key concepts only

**Content**:
```
المحكمة العليا تراجع قوانين النقل في السعودية ضمن رؤية 2030 لتطوير القضاء وتحسين العدالة.
```

**Key Features**:
- Only 18 words (original: ~90 words)
- 80% reduction in length
- All key concepts present:
  - المحكمة العليا (Supreme Court)
  - قوانين النقل (Transport laws)
  - رؤية 2030 (Vision 2030)
  - القضاء (Judiciary)
  - العدالة (Justice)

**Expected Similarity**: 70-78%
**Should Be Caught**: ✅ YES (Just above threshold)

---

## ✅ Test Case 5: Expanded Version with Additional Details

**Purpose**: Test if added filler content affects detection

**Content**:
```
أعلنت المحكمة العليا في المملكة العربية السعودية، في بيان رسمي صدر اليوم، عن قرارها بمراجعة شاملة لقوانين النقل والمواصلات الحالية. ويأتي هذا الإعلان في سياق الجهود المستمرة لتحسين الخدمات القضائية المقدمة للمواطنين وتطوير منظومة العدالة بشكل عام في المملكة. ويعتبر هذا القرار جزءاً لا يتجزأ من رؤية المملكة 2030 الطموحة التي تهدف إلى تطوير وتحديث القطاع القضائي بشكل كامل وتعزيز سيادة القانون في جميع مناحي الحياة. وبحسب المصادر المطلعة، سيتم البدء في تطبيق هذه التحديثات والتعديلات القانونية خلال الأشهر المقبلة، مع التأكيد على توفير كافة البرامج التدريبية اللازمة للكوادر القضائية العاملة في مختلف المحاكم.
```

**Key Features**:
- 150% longer than original
- Added formal phrases: "في بيان رسمي", "بحسب المصادر المطلعة"
- Expanded descriptions
- Same core message buried in additional text

**Expected Similarity**: 82-92%
**Should Be Caught**: ✅ YES (AraBERT should focus on core content)

---

## 🧪 Testing Instructions

### Method 1: Via Web Interface

1. Navigate to: `http://localhost:8000/`
2. Paste each test case into the verification form
3. Submit and observe results
4. Expected: All 5 should show high similarity (70%+)

### Method 2: Via cURL

```bash
# Test Case 1
curl -X POST http://localhost:8000/verify \
  -d "content=المحكمة العليا أعلنت عن تحديث قوانين النقل والمواصلات لتحسين الخدمات القضائية في السعودية. القرار جزء من رؤية 2030 لتطوير القطاع القضائي."

# Test Case 2
curl -X POST http://localhost:8000/verify \
  -d "content=قامت أعلى محكمة في البلاد بالإعلان عن مراجعة شاملة للأنظمة المتعلقة بوسائل النقل والمواصلات، وذلك بغرض تطوير الخدمات في الجهاز القضائي وتحسين مستوى العدالة في المملكة."

# Test Case 3
curl -X POST http://localhost:8000/verify \
  -d "content=في إطار رؤية 2030 للمملكة العربية السعودية، تم الإعلان من قبل المحكمة العليا عن مراجعة القوانين الخاصة بالنقل والمواصلات. الهدف الأساسي من هذه المراجعة هو تطوير العدالة وتحسين الخدمات القضائية المقدمة للمواطنين."

# Test Case 4
curl -X POST http://localhost:8000/verify \
  -d "content=المحكمة العليا تراجع قوانين النقل في السعودية ضمن رؤية 2030 لتطوير القضاء وتحسين العدالة."

# Test Case 5
curl -X POST http://localhost:8000/verify \
  -d "content=أعلنت المحكمة العليا في المملكة العربية السعودية، في بيان رسمي صدر اليوم، عن قرارها بمراجعة شاملة لقوانين النقل والمواصلات الحالية. ويأتي هذا الإعلان في سياق الجهود المستمرة لتحسين الخدمات القضائية المقدمة للمواطنين."
```

### Method 3: Via Tinker

```bash
php artisan tinker
```

```php
use App\Http\Controllers\Web\VerificationController;
use Illuminate\Http\Request;

$controller = app(VerificationController::class);

$testCases = [
    "المحكمة العليا أعلنت عن تحديث قوانين النقل والمواصلات لتحسين الخدمات القضائية في السعودية. القرار جزء من رؤية 2030 لتطوير القطاع القضائي.",
    // Add others...
];

foreach ($testCases as $index => $content) {
    $request = new Request(['content' => $content]);
    $result = $controller->verify($request);
    echo "Test Case " . ($index + 1) . " - Similarity: " . ($result->getData()['highest_similarity'] ?? 'N/A') . "\n";
}
```

---

## 📊 Expected Results Summary

| Test Case | Type | Length | Expected Similarity | Should Match |
|-----------|------|--------|---------------------|--------------|
| Original | Exact | 100% | 100% | ✅ YES |
| Case 1 | Summarized | 30% | 75-85% | ✅ YES |
| Case 2 | Paraphrased | 70% | 80-90% | ✅ YES |
| Case 3 | Reordered | 60% | 75-85% | ✅ YES |
| Case 4 | Minimal | 20% | 70-78% | ✅ YES |
| Case 5 | Expanded | 150% | 82-92% | ✅ YES |

---

## 🎯 Why These Should All Be Caught

### AraBERT's Semantic Understanding

AraBERT (Arabic BERT) is a transformer-based model that understands:

1. **Semantic Similarity**: Not just word matching, but meaning
2. **Context**: Understands relationships between words
3. **Synonyms**: Recognizes different words with same meaning
4. **Word Order**: Flexible to reordering as long as meaning preserved
5. **Length Variation**: Can match long/short texts with same core message

### Key Concept Overlap

All test cases maintain these critical elements:

- **المحكمة العليا** (Supreme Court) - Primary actor
- **قوانين النقل** (Transport laws) - Subject matter
- **رؤية 2030** (Vision 2030) - Context/motivation
- **القطاع القضائي** (Judicial sector) - Domain
- **تطوير/تحسين** (Development/improvement) - Action

### FULLTEXT + AI Strategy

1. **FULLTEXT** catches Test Cases 1, 3, 5 (shared keywords)
2. **AraBERT** catches Test Cases 2, 4 (semantic similarity)
3. Combined approach ensures **all variations detected**

---

## 🔧 Improving Match Rate

If some test cases are NOT caught, you can:

### 1. Lower Threshold

```php
// In VerificationController.php
return $this->pythonAI->verifyArabicNewsWithCandidates(
    text: $content,
    candidates: $candidates,
    threshold: 0.65,  // Lowered from 0.70 to catch more
    topK: 5
);
```

### 2. Increase Candidates

```php
// In VerificationController.php
$fullTextMatches = \App\Models\DatasetFakeNews::query()
    // ...
    ->limit(75)  // Increased from 50
    ->get();

// Add 75 random samples instead of 50
$remainingSlots = 150 - count($candidates);  // Total 150 instead of 100
```

### 3. Enhance FULLTEXT Search

```php
// Use BOOLEAN mode for better keyword matching
->whereRaw('MATCH(title, content) AGAINST(? IN BOOLEAN MODE)', [
    '+المحكمة +العليا +قوانين +النقل +رؤية +2030'
])
```

---

## 📝 Adding Original News to Database

To test, first add the original fake news:

```bash
php artisan tinker
```

```php
use App\Models\DatasetFakeNews;

DatasetFakeNews::create([
    'title' => 'المحكمة العليا تعلن مراجعة قوانين النقل والمواصلات',
    'content' => 'أعلن المحكمة العليا عن مراجعة قوانين النقل والمواصلات بهدف تحسين الخدمات القضائية وتطوير العدالة في المملكة العربية السعودية. هذا القرار يأتي في إطار رؤية المملكة 2030 لتطوير القطاع القضائي وتعزيز سيادة القانون. سيتم تطبيق هذه التحديثات خلال الأشهر القادمة مع توفير التدريب اللازم للكوادر القضائية. كما سيتم إتاحة معلومات تفصيلية للمواطنين حول هذه التغييرات من خلال المواقع الرسمية والمنصات الإلكترونية. للمزيد من التفاصيل والمعلومات الرسمية، يرجى مراجعة الموقع الإلكتروني الرسمي للجهة المختصة.',
    'language' => 'ar',
    'confidence_score' => 0.95,
    'origin_dataset_name' => 'test_case_legal',
    'detected_at' => now(),
    'added_by_ai' => false,
]);

echo "Fake news added successfully!\n";
```

---

## ✅ Success Criteria

A successful test run should show:

- ✅ All 5 test cases detected as "Potentially Fake"
- ✅ Similarity scores >= 70%
- ✅ Original news appears in "Similar News" results
- ✅ Processing time < 5 seconds for each
- ✅ No false negatives

---

## 🎉 Conclusion

These 5 test cases comprehensively test the Arabic semantic matching capabilities:

1. **Summarization handling** ✓
2. **Synonym recognition** ✓
3. **Structure variation** ✓
4. **Minimal content matching** ✓
5. **Expanded content filtering** ✓

The AraBERT model should successfully catch all variations thanks to its deep semantic understanding of Arabic text!
