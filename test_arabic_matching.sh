#!/bin/bash

# Arabic Semantic Matching Test Script
# This script adds the original fake news to the database and tests all 5 variations

echo "🧪 Arabic Semantic Matching Test Suite"
echo "======================================"
echo ""

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Step 1: Add original fake news to database
echo "📝 Step 1: Adding original fake news to database..."
php artisan tinker --execute="
use App\Models\DatasetFakeNews;

// Check if already exists
\$existing = DatasetFakeNews::where('title', 'LIKE', '%المحكمة العليا تعلن مراجعة قوانين النقل%')->first();

if (\$existing) {
    echo '⚠️  Original fake news already exists (ID: ' . \$existing->id . ')\n';
} else {
    DatasetFakeNews::create([
        'title' => 'المحكمة العليا تعلن مراجعة قوانين النقل والمواصلات',
        'content' => 'أعلن المحكمة العليا عن مراجعة قوانين النقل والمواصلات بهدف تحسين الخدمات القضائية وتطوير العدالة في المملكة العربية السعودية. هذا القرار يأتي في إطار رؤية المملكة 2030 لتطوير القطاع القضائي وتعزيز سيادة القانون. سيتم تطبيق هذه التحديثات خلال الأشهر القادمة مع توفير التدريب اللازم للكوادر القضائية. كما سيتم إتاحة معلومات تفصيلية للمواطنين حول هذه التغييرات من خلال المواقع الرسمية والمنصات الإلكترونية. للمزيد من التفاصيل والمعلومات الرسمية، يرجى مراجعة الموقع الإلكتروني الرسمي للجهة المختصة.',
        'language' => 'ar',
        'confidence_score' => 0.95,
        'origin_dataset_name' => 'test_case_legal',
        'detected_at' => now(),
        'added_by_ai' => false,
    ]);
    echo '✅ Original fake news added successfully!\n';
}
"

echo ""
echo "🧪 Step 2: Testing 5 variations..."
echo ""

# Test Case 1: Summarized Version
echo -e "${YELLOW}Test Case 1: Summarized Version (Short)${NC}"
curl -s -X POST http://localhost:8000/verify \
  -H "Content-Type: application/x-www-form-urlencoded" \
  --data-urlencode "content=المحكمة العليا أعلنت عن تحديث قوانين النقل والمواصلات لتحسين الخدمات القضائية في السعودية. القرار جزء من رؤية 2030 لتطوير القطاع القضائي." \
  | grep -o '"highest_similarity":[0-9.]*' || echo "❌ Request failed"
echo ""

# Test Case 2: Paraphrased
echo -e "${YELLOW}Test Case 2: Paraphrased with Different Words${NC}"
curl -s -X POST http://localhost:8000/verify \
  -H "Content-Type: application/x-www-form-urlencoded" \
  --data-urlencode "content=قامت أعلى محكمة في البلاد بالإعلان عن مراجعة شاملة للأنظمة المتعلقة بوسائل النقل والمواصلات، وذلك بغرض تطوير الخدمات في الجهاز القضائي وتحسين مستوى العدالة في المملكة. هذه الخطوة تأتي ضمن خطة المملكة للتنمية 2030 التي تستهدف النهوض بالقطاع القضائي وترسيخ حكم القانون." \
  | grep -o '"highest_similarity":[0-9.]*' || echo "❌ Request failed"
echo ""

# Test Case 3: Reordered
echo -e "${YELLOW}Test Case 3: Reordered Structure${NC}"
curl -s -X POST http://localhost:8000/verify \
  -H "Content-Type: application/x-www-form-urlencoded" \
  --data-urlencode "content=في إطار رؤية 2030 للمملكة العربية السعودية، تم الإعلان من قبل المحكمة العليا عن مراجعة القوانين الخاصة بالنقل والمواصلات. الهدف الأساسي من هذه المراجعة هو تطوير العدالة وتحسين الخدمات القضائية المقدمة للمواطنين. ومن المقرر أن يتم تطبيق التحديثات الجديدة خلال الفترة القادمة مع ضمان تدريب العاملين في القطاع القضائي بشكل مناسب." \
  | grep -o '"highest_similarity":[0-9.]*' || echo "❌ Request failed"
echo ""

# Test Case 4: Minimal
echo -e "${YELLOW}Test Case 4: Very Brief Summary (Minimal)${NC}"
curl -s -X POST http://localhost:8000/verify \
  -H "Content-Type: application/x-www-form-urlencoded" \
  --data-urlencode "content=المحكمة العليا تراجع قوانين النقل في السعودية ضمن رؤية 2030 لتطوير القضاء وتحسين العدالة." \
  | grep -o '"highest_similarity":[0-9.]*' || echo "❌ Request failed"
echo ""

# Test Case 5: Expanded
echo -e "${YELLOW}Test Case 5: Expanded Version${NC}"
curl -s -X POST http://localhost:8000/verify \
  -H "Content-Type: application/x-www-form-urlencoded" \
  --data-urlencode "content=أعلنت المحكمة العليا في المملكة العربية السعودية، في بيان رسمي صدر اليوم، عن قرارها بمراجعة شاملة لقوانين النقل والمواصلات الحالية. ويأتي هذا الإعلان في سياق الجهود المستمرة لتحسين الخدمات القضائية المقدمة للمواطنين وتطوير منظومة العدالة بشكل عام في المملكة. ويعتبر هذا القرار جزءاً لا يتجزأ من رؤية المملكة 2030 الطموحة التي تهدف إلى تطوير وتحديث القطاع القضائي بشكل كامل وتعزيز سيادة القانون في جميع مناحي الحياة." \
  | grep -o '"highest_similarity":[0-9.]*' || echo "❌ Request failed"
echo ""

echo "======================================"
echo "✅ Test suite completed!"
echo ""
echo "📊 Expected Results:"
echo "   - All test cases should show similarity >= 0.70 (70%)"
echo "   - All should be flagged as 'Potentially Fake'"
echo ""
echo "📝 For detailed results, check the verification-result page in your browser"
echo "   or review the logs at: storage/logs/laravel.log"
