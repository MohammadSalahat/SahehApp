<?php

/**
 * Arabic Semantic Matching Test Script
 *
 * This script tests 5 variations of a fake news article to ensure
 * the AraBERT semantic matching catches all variations.
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\DatasetFakeNews;

echo "🧪 Arabic Semantic Matching Test Suite\n";
echo str_repeat('=', 50)."\n\n";

// Step 1: Add original fake news to database
echo "📝 Step 1: Adding original fake news to database...\n";

$originalTitle = 'المحكمة العليا تعلن مراجعة قوانين النقل والمواصلات';
$originalContent = 'أعلن المحكمة العليا عن مراجعة قوانين النقل والمواصلات بهدف تحسين الخدمات القضائية وتطوير العدالة في المملكة العربية السعودية. هذا القرار يأتي في إطار رؤية المملكة 2030 لتطوير القطاع القضائي وتعزيز سيادة القانون. سيتم تطبيق هذه التحديثات خلال الأشهر القادمة مع توفير التدريب اللازم للكوادر القضائية. كما سيتم إتاحة معلومات تفصيلية للمواطنين حول هذه التغييرات من خلال المواقع الرسمية والمنصات الإلكترونية. للمزيد من التفاصيل والمعلومات الرسمية، يرجى مراجعة الموقع الإلكتروني الرسمي للجهة المختصة.';

// Check if already exists
$existing = DatasetFakeNews::where('title', 'LIKE', '%المحكمة العليا تعلن مراجعة قوانين النقل%')->first();

if ($existing) {
    echo "⚠️  Original fake news already exists (ID: {$existing->id})\n";
    $fakeNewsId = $existing->id;
} else {
    $fakeNews = DatasetFakeNews::create([
        'title' => $originalTitle,
        'content' => $originalContent,
        'language' => 'ar',
        'confidence_score' => 0.95,
        'origin_dataset_name' => 'test_case_legal',
        'detected_at' => now(),
        'added_by_ai' => false,
    ]);
    $fakeNewsId = $fakeNews->id;
    echo "✅ Original fake news added successfully! (ID: {$fakeNewsId})\n";
}

echo "\n";

// Define test cases
$testCases = [
    [
        'name' => 'Test Case 1: Summarized Version (Short)',
        'description' => 'Tests if system catches abbreviated versions',
        'content' => 'المحكمة العليا أعلنت عن تحديث قوانين النقل والمواصلات لتحسين الخدمات القضائية في السعودية. القرار جزء من رؤية 2030 لتطوير القطاع القضائي.',
        'expected_similarity' => '75-85%',
    ],
    [
        'name' => 'Test Case 2: Paraphrased with Different Words',
        'description' => 'Tests semantic understanding with synonym replacement',
        'content' => 'قامت أعلى محكمة في البلاد بالإعلان عن مراجعة شاملة للأنظمة المتعلقة بوسائل النقل والمواصلات، وذلك بغرض تطوير الخدمات في الجهاز القضائي وتحسين مستوى العدالة في المملكة. هذه الخطوة تأتي ضمن خطة المملكة للتنمية 2030 التي تستهدف النهوض بالقطاع القضائي وترسيخ حكم القانون.',
        'expected_similarity' => '80-90%',
    ],
    [
        'name' => 'Test Case 3: Reordered Structure',
        'description' => 'Tests if different sentence order is detected',
        'content' => 'في إطار رؤية 2030 للمملكة العربية السعودية، تم الإعلان من قبل المحكمة العليا عن مراجعة القوانين الخاصة بالنقل والمواصلات. الهدف الأساسي من هذه المراجعة هو تطوير العدالة وتحسين الخدمات القضائية المقدمة للمواطنين. ومن المقرر أن يتم تطبيق التحديثات الجديدة خلال الفترة القادمة مع ضمان تدريب العاملين في القطاع القضائي بشكل مناسب.',
        'expected_similarity' => '75-85%',
    ],
    [
        'name' => 'Test Case 4: Very Brief Summary (Minimal)',
        'description' => 'Tests minimum viable match with key concepts only',
        'content' => 'المحكمة العليا تراجع قوانين النقل في السعودية ضمن رؤية 2030 لتطوير القضاء وتحسين العدالة.',
        'expected_similarity' => '70-78%',
    ],
    [
        'name' => 'Test Case 5: Expanded Version',
        'description' => 'Tests if added filler content affects detection',
        'content' => 'أعلنت المحكمة العليا في المملكة العربية السعودية، في بيان رسمي صدر اليوم، عن قرارها بمراجعة شاملة لقوانين النقل والمواصلات الحالية. ويأتي هذا الإعلان في سياق الجهود المستمرة لتحسين الخدمات القضائية المقدمة للمواطنين وتطوير منظومة العدالة بشكل عام في المملكة. ويعتبر هذا القرار جزءاً لا يتجزأ من رؤية المملكة 2030 الطموحة التي تهدف إلى تطوير وتحديث القطاع القضائي بشكل كامل وتعزيز سيادة القانون في جميع مناحي الحياة.',
        'expected_similarity' => '82-92%',
    ],
];

echo "🧪 Step 2: Running test cases...\n";
echo str_repeat('-', 50)."\n\n";

$results = [];
$passCount = 0;
$failCount = 0;

foreach ($testCases as $index => $testCase) {
    $testNum = $index + 1;
    echo "📋 {$testCase['name']}\n";
    echo "   {$testCase['description']}\n";
    echo "   Expected: {$testCase['expected_similarity']}\n";
    echo '   Content length: '.mb_strlen($testCase['content'])." characters\n";
    echo '   Testing... ';

    // Save content to temp file for curl
    $tempFile = sys_get_temp_dir()."/test_case_{$testNum}.txt";
    file_put_contents($tempFile, $testCase['content']);

    // Make HTTP request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/verify');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['content' => $testCase['content']]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Parse response (looking for similarity score in HTML or JSON)
    if ($httpCode === 200 && $response) {
        // Try to extract highest_similarity from response
        if (preg_match('/highest_similarity["\']?\s*[:=]\s*([0-9.]+)/', $response, $matches)) {
            $similarity = floatval($matches[1]);
            $similarityPercent = round($similarity * 100, 1);

            $isPotentiallyFake = $similarity >= 0.70;
            $status = $isPotentiallyFake ? '✅ PASS' : '❌ FAIL';

            if ($isPotentiallyFake) {
                $passCount++;
            } else {
                $failCount++;
            }

            echo "{$status} (Similarity: {$similarityPercent}%)\n";

            $results[] = [
                'test' => $testCase['name'],
                'similarity' => $similarity,
                'passed' => $isPotentiallyFake,
            ];
        } else {
            echo "⚠️  Could not parse similarity score\n";
            $failCount++;
        }
    } else {
        echo "❌ HTTP Error (Code: {$httpCode})\n";
        $failCount++;
    }

    // Cleanup
    if (file_exists($tempFile)) {
        unlink($tempFile);
    }

    echo "\n";

    // Small delay to avoid overwhelming the server
    sleep(1);
}

// Summary
echo str_repeat('=', 50)."\n";
echo "📊 Test Results Summary\n";
echo str_repeat('=', 50)."\n\n";

foreach ($results as $result) {
    $status = $result['passed'] ? '✅' : '❌';
    $similarityPercent = round($result['similarity'] * 100, 1);
    echo "{$status} {$result['test']}: {$similarityPercent}%\n";
}

echo "\n";
echo 'Total Tests: '.count($testCases)."\n";
echo "Passed: {$passCount} ✅\n";
echo "Failed: {$failCount} ❌\n";
echo "\n";

if ($failCount === 0) {
    echo "🎉 All tests passed! Arabic semantic matching is working perfectly.\n";
} else {
    echo "⚠️  Some tests failed. Consider:\n";
    echo "   - Lowering threshold from 0.70 to 0.65\n";
    echo "   - Increasing candidate count from 100 to 150\n";
    echo "   - Checking if Python AI service is running\n";
    echo "   - Reviewing logs at: storage/logs/laravel.log\n";
}

echo "\n";
echo "💡 Tip: For detailed results, visit the verification page in your browser\n";
echo "   and manually test each case.\n";

exit($failCount > 0 ? 1 : 0);
