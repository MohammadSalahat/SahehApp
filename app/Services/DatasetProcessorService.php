<?php

namespace App\Services;

use App\Models\DatasetFakeNews;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatasetProcessorService
{
    /**
     * KSA Legal keywords in Arabic
     */
    private array $ksaLegalKeywords = [
        'السعودية', 'السعودي', 'المملكة', 'الرياض',
        'وزارة العدل', 'النيابة', 'المحكمة', 'القضاء',
        'نظام', 'قانون', 'لائحة', 'قرار', 'مرسوم',
        'الديوان الملكي', 'مجلس الوزراء', 'هيئة',
        'محكمة', 'قاضي', 'قضية', 'حكم', 'تشريع',
        'جدة', 'الدمام', 'مكة', 'المدينة',
    ];

    /**
     * Check if text contains Arabic characters
     */
    private function isArabicText(string $text): bool
    {
        // Check for Arabic Unicode range (U+0600 to U+06FF)
        return preg_match('/[\x{0600}-\x{06FF}]/u', $text) > 0;
    }

    /**
     * Check if text is related to KSA legal news
     */
    private function isKSALegalRelated(string $text): bool
    {
        $textLower = mb_strtolower($text, 'UTF-8');

        $matchCount = 0;
        foreach ($this->ksaLegalKeywords as $keyword) {
            if (mb_strpos($textLower, $keyword) !== false) {
                $matchCount++;
            }
        }

        // Require at least 2 KSA legal keywords to be considered relevant
        return $matchCount >= 2;
    }

    /**
     * Process LIAR dataset
     *
     * @param  string  $filePath  Path to LIAR CSV file
     * @param  bool  $arabicOnly  Only process Arabic text
     * @param  bool  $ksaLegalOnly  Only process KSA legal news
     * @return array Processing statistics
     */
    public function processLiarDataset(
        string $filePath,
        bool $arabicOnly = true,
        bool $ksaLegalOnly = true
    ): array {
        Log::info("Processing LIAR dataset from: {$filePath}");
        Log::info("Filters: Arabic only={$arabicOnly}, KSA legal only={$ksaLegalOnly}");

        if (! file_exists($filePath)) {
            Log::error("LIAR dataset file not found: {$filePath}");

            return [
                'success' => false,
                'error' => 'File not found',
                'processed' => 0,
            ];
        }

        $stats = [
            'processed' => 0,
            'filtered_not_arabic' => 0,
            'filtered_not_ksa' => 0,
            'skipped_too_short' => 0,
            'skipped_duplicate' => 0,
            'total_rows' => 0,
        ];

        try {
            DB::beginTransaction();

            $file = fopen($filePath, 'r');
            $headers = fgetcsv($file); // Read header row

            while (($row = fgetcsv($file)) !== false) {
                $stats['total_rows']++;

                try {
                    // Map CSV columns (adjust based on your LIAR CSV structure)
                    $data = array_combine($headers, $row);

                    $title = $data['subject'] ?? 'Untitled';
                    $content = $data['statement'] ?? '';
                    $label = strtolower($data['label'] ?? '');

                    // Only process fake news
                    if (! in_array($label, ['false', 'pants-fire', 'barely-true', 'fake'])) {
                        continue;
                    }

                    // FILTER 1: Check if Arabic
                    if ($arabicOnly && ! $this->isArabicText($content)) {
                        $stats['filtered_not_arabic']++;

                        continue;
                    }

                    // FILTER 2: Check if KSA legal related
                    if ($ksaLegalOnly && ! $this->isKSALegalRelated($content.' '.$title)) {
                        $stats['filtered_not_ksa']++;

                        continue;
                    }

                    // Check minimum length
                    if (mb_strlen($content) < 20) {
                        $stats['skipped_too_short']++;

                        continue;
                    }

                    // Check for duplicates
                    $contentHash = hash('sha256', $content);
                    $exists = DatasetFakeNews::where('content_hash', $contentHash)->exists();

                    if ($exists) {
                        $stats['skipped_duplicate']++;

                        continue;
                    }

                    // Create new record
                    DatasetFakeNews::create([
                        'title' => substr($title, 0, 500),
                        'content' => $content,
                        'detected_at' => now(),
                        'confidence_score' => $this->labelToScore($label),
                        'origin_dataset_name' => 'LIAR',
                        'added_by_ai' => false,
                        'content_hash' => $contentHash,
                    ]);

                    $stats['processed']++;

                    // Log progress every 100 records
                    if ($stats['processed'] % 100 === 0) {
                        Log::info("LIAR: Processed {$stats['processed']} records");
                    }

                } catch (\Exception $e) {
                    Log::error("Error processing LIAR row: {$e->getMessage()}");

                    continue;
                }
            }

            fclose($file);
            DB::commit();

            Log::info('LIAR dataset processing completed', $stats);
            $stats['success'] = true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error processing LIAR dataset: {$e->getMessage()}");
            $stats['success'] = false;
            $stats['error'] = $e->getMessage();
        }

        return $stats;
    }

    /**
     * Process CredBank dataset
     *
     * @param  string  $filePath  Path to CredBank CSV file
     * @param  bool  $arabicOnly  Only process Arabic text
     * @param  bool  $ksaLegalOnly  Only process KSA legal news
     * @return array Processing statistics
     */
    public function processCredBankDataset(
        string $filePath,
        bool $arabicOnly = true,
        bool $ksaLegalOnly = true
    ): array {
        Log::info("Processing CredBank dataset from: {$filePath}");
        Log::info("Filters: Arabic only={$arabicOnly}, KSA legal only={$ksaLegalOnly}");

        if (! file_exists($filePath)) {
            Log::error("CredBank dataset file not found: {$filePath}");

            return [
                'success' => false,
                'error' => 'File not found',
                'processed' => 0,
            ];
        }

        $stats = [
            'processed' => 0,
            'filtered_not_arabic' => 0,
            'filtered_not_ksa' => 0,
            'skipped_too_short' => 0,
            'skipped_duplicate' => 0,
            'total_rows' => 0,
        ];

        try {
            DB::beginTransaction();

            $file = fopen($filePath, 'r');
            $headers = fgetcsv($file);

            while (($row = fgetcsv($file)) !== false) {
                $stats['total_rows']++;

                try {
                    $data = array_combine($headers, $row);

                    $title = $data['topic'] ?? $data['title'] ?? 'Untitled';
                    $content = $data['text'] ?? $data['content'] ?? '';
                    $credibility = floatval($data['credibility'] ?? 0);

                    // Process only low credibility (fake news)
                    if ($credibility >= 0.3) {
                        continue;
                    }

                    // FILTER 1: Check if Arabic
                    if ($arabicOnly && ! $this->isArabicText($content)) {
                        $stats['filtered_not_arabic']++;

                        continue;
                    }

                    // FILTER 2: Check if KSA legal related
                    if ($ksaLegalOnly && ! $this->isKSALegalRelated($content.' '.$title)) {
                        $stats['filtered_not_ksa']++;

                        continue;
                    }

                    // Check minimum length
                    if (mb_strlen($content) < 20) {
                        $stats['skipped_too_short']++;

                        continue;
                    }

                    // Check for duplicates
                    $contentHash = hash('sha256', $content);
                    $exists = DatasetFakeNews::where('content_hash', $contentHash)->exists();

                    if ($exists) {
                        $stats['skipped_duplicate']++;

                        continue;
                    }

                    // Create new record
                    DatasetFakeNews::create([
                        'title' => substr($title, 0, 500),
                        'content' => $content,
                        'detected_at' => now(),
                        'confidence_score' => abs($credibility),
                        'origin_dataset_name' => 'CredBank',
                        'added_by_ai' => false,
                        'content_hash' => $contentHash,
                    ]);

                    $stats['processed']++;

                    if ($stats['processed'] % 100 === 0) {
                        Log::info("CredBank: Processed {$stats['processed']} records");
                    }

                } catch (\Exception $e) {
                    Log::error("Error processing CredBank row: {$e->getMessage()}");

                    continue;
                }
            }

            fclose($file);
            DB::commit();

            Log::info('CredBank dataset processing completed', $stats);
            $stats['success'] = true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error processing CredBank dataset: {$e->getMessage()}");
            $stats['success'] = false;
            $stats['error'] = $e->getMessage();
        }

        return $stats;
    }

    /**
     * Convert label to confidence score
     */
    private function labelToScore(string $label): float
    {
        return match (strtolower($label)) {
            'false', 'pants-fire', 'fake' => 1.0,
            'mostly-false' => 0.8,
            'barely-true' => 0.7,
            default => 0.5,
        };
    }

    /**
     * Process Fake-Real News Dataset (Kaggle format)
     */
    public function processFakeRealNewsDataset(
        string $filePath,
        bool $arabicOnly = true,
        bool $ksaLegalOnly = true
    ): array {
        $stats = [
            'success' => true,
            'processed' => 0,
            'imported' => 0,
            'filtered_arabic' => 0,
            'filtered_ksa' => 0,
            'too_short' => 0,
            'duplicates' => 0,
            'error' => null,
        ];

        try {
            $handle = fopen($filePath, 'r');
            if (! $handle) {
                throw new \Exception("Could not open file: {$filePath}");
            }

            // Read header
            $header = fgetcsv($handle);

            // Expected columns: id, title, text, label
            if (! $header || ! in_array('title', $header) || ! in_array('text', $header) || ! in_array('label', $header)) {
                throw new \Exception('Invalid CSV format. Expected columns: id, title, text, label');
            }

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) !== count($header)) {
                    continue; // Skip malformed rows
                }

                $data = array_combine($header, $row);
                $stats['processed']++;

                // Extract data
                $title = trim($data['title'] ?? '');
                $content = trim($data['text'] ?? '');
                $label = strtolower(trim($data['label'] ?? ''));

                // Combine title and content
                $fullText = $title.' '.$content;

                // Skip if too short
                if (strlen($fullText) < 50) {
                    $stats['too_short']++;

                    continue;
                }

                // Apply Arabic filter
                if ($arabicOnly && ! $this->isArabicText($fullText)) {
                    $stats['filtered_arabic']++;

                    continue;
                }

                // Apply KSA legal filter
                if ($ksaLegalOnly && ! $this->isKSALegalRelated($fullText)) {
                    $stats['filtered_ksa']++;

                    continue;
                }

                // Check for duplicates
                if (DatasetFakeNews::where('title', $title)->exists()) {
                    $stats['duplicates']++;

                    continue;
                }

                // Determine if fake news (label: FAKE = 1, REAL = 0)
                $isFake = ($label === 'fake') ? 1 : 0;
                $confidence = $isFake ? 1.0 : 0.0; // Binary confidence

                // Store in database
                DatasetFakeNews::create([
                    'title' => $title,
                    'content' => $content,
                    'is_fake' => $isFake,
                    'confidence_score' => $confidence,
                    'source' => 'fake_real_news_kaggle',
                    'category' => 'general',
                    'metadata' => json_encode([
                        'id' => $data['id'] ?? null,
                        'original_label' => $label,
                        'dataset' => 'fake_real_news_kaggle',
                    ]),
                ]);

                $stats['imported']++;
            }

            fclose($handle);

        } catch (\Exception $e) {
            $stats['success'] = false;
            $stats['error'] = $e->getMessage();
        }

        return $stats;
    }

    /**
     * Process KSA-specific dataset
     */
    public function processKSADataset(
        string $filePath,
        bool $arabicOnly = true,
        bool $ksaLegalOnly = true
    ): array {
        $stats = [
            'success' => true,
            'processed' => 0,
            'imported' => 0,
            'filtered_arabic' => 0,
            'filtered_ksa' => 0,
            'too_short' => 0,
            'duplicates' => 0,
            'error' => null,
        ];

        try {
            $handle = fopen($filePath, 'r');
            if (! $handle) {
                throw new \Exception("Could not open file: {$filePath}");
            }

            // Read header
            $header = fgetcsv($handle);

            if (! $header) {
                throw new \Exception('Invalid CSV format - no header found');
            }

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) !== count($header)) {
                    continue; // Skip malformed rows
                }

                $data = array_combine($header, $row);
                $stats['processed']++;

                // Extract data
                $title = trim($data['title'] ?? '');
                $content = trim($data['content'] ?? '');
                $label = strtolower(trim($data['label'] ?? ''));
                $category = trim($data['category'] ?? 'general');
                $region = trim($data['region'] ?? 'unknown');

                // Combine title and content
                $fullText = $title.' '.$content;

                // Skip if too short
                if (strlen($fullText) < 20) {
                    $stats['too_short']++;

                    continue;
                }

                // Apply Arabic filter (more lenient for KSA data)
                if ($arabicOnly && ! $this->isArabicText($fullText) && ! $this->hasArabicContent($fullText)) {
                    $stats['filtered_arabic']++;

                    continue;
                }

                // KSA filter is always applied for KSA datasets
                if (! $this->isKSALegalRelated($fullText) && ! $this->hasKSAContent($fullText)) {
                    $stats['filtered_ksa']++;

                    continue;
                }

                // Check for duplicates using content hash (more accurate)
                $contentHash = hash('sha256', $content);
                if (DatasetFakeNews::where('content_hash', $contentHash)->exists()) {
                    $stats['duplicates']++;

                    continue;
                }

                // Determine if fake news
                $isFake = in_array($label, ['fake', 'false', '1']) ? 1 : 0;
                $confidence = $isFake ? 0.95 : 0.05; // High confidence for KSA-curated data

                // Store in database using correct columns for datasets_fake_news table
                DatasetFakeNews::create([
                    'title' => $title,
                    'content' => $content,
                    'language' => $this->detectLanguage($fullText),
                    'detected_at' => now(),
                    'confidence_score' => $confidence,
                    'origin_dataset_name' => 'KSA_COMPREHENSIVE',
                    'added_by_ai' => false, // This is from a curated dataset
                    'content_hash' => $contentHash,
                ]);

                $stats['imported']++;
            }

            fclose($handle);

        } catch (\Exception $e) {
            $stats['success'] = false;
            $stats['error'] = $e->getMessage();
        }

        return $stats;
    }

    /**
     * Check if text has Arabic content (more flexible)
     */
    private function hasArabicContent(string $text): bool
    {
        // Check for at least some Arabic characters or KSA-related English terms
        return preg_match('/[\x{0600}-\x{06FF}]/u', $text) ||
               $this->containsKSAEnglishTerms($text);
    }

    /**
     * Check if text has KSA-specific content
     */
    private function hasKSAContent(string $text): bool
    {
        $textLower = mb_strtolower($text, 'UTF-8');

        $ksaTerms = [
            'saudi', 'arabia', 'riyadh', 'jeddah', 'mecca', 'medina',
            'السعودية', 'السعودي', 'المملكة', 'الرياض', 'جدة', 'مكة',
            'vision 2030', 'رؤية 2030', 'neom', 'نيوم', 'ministry of justice',
            'وزارة العدل', 'aramco', 'أرامكو',
        ];

        foreach ($ksaTerms as $term) {
            if (mb_strpos($textLower, mb_strtolower($term, 'UTF-8')) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check for KSA-related English terms
     */
    private function containsKSAEnglishTerms(string $text): bool
    {
        $textLower = strtolower($text);
        $englishTerms = [
            'saudi arabia', 'kingdom of saudi', 'riyadh', 'jeddah', 'mecca', 'medina',
            'vision 2030', 'neom', 'ministry of justice', 'public prosecution',
            'aramco', 'royal court', 'council of ministers',
        ];

        foreach ($englishTerms as $term) {
            if (strpos($textLower, $term) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Process all configured datasets
     */
    public function processAllDatasets(): array
    {
        $results = [];

        // Try new LIAR dataset first, fall back to old one
        $liarPaths = [
            storage_path('app/datasets/liar/train.csv'),
            storage_path('app/datasets/liar/politifact_fake.csv'),
        ];

        $liarPath = null;
        foreach ($liarPaths as $path) {
            if (file_exists($path)) {
                $liarPath = $path;
                break;
            }
        }

        if ($liarPath) {
            $results['liar'] = $this->processLiarDataset($liarPath);
        } else {
            $results['liar'] = ['success' => false, 'error' => 'File not found', 'processed' => 0];
        }

        $credBankPath = storage_path('app/datasets/credbank/credbank_sample.csv');
        if (file_exists($credBankPath)) {
            $results['credbank'] = $this->processCredBankDataset($credBankPath);
        } else {
            $results['credbank'] = ['success' => false, 'error' => 'File not found', 'processed' => 0];
        }

        // Add the new comprehensive fake news dataset
        $fakeRealNewsPath = storage_path('app/datasets/fake_real_news_kaggle.csv');
        if (file_exists($fakeRealNewsPath)) {
            $results['fake_real_news'] = $this->processFakeRealNewsDataset($fakeRealNewsPath, true, true);
        } else {
            $results['fake_real_news'] = ['success' => false, 'error' => 'File not found', 'processed' => 0];
        }

        // Add KSA-specific comprehensive dataset
        $ksaPath = storage_path('app/datasets/ksa_comprehensive.csv');
        if (file_exists($ksaPath)) {
            $results['ksa_comprehensive'] = $this->processKSADataset($ksaPath, true, true);
        } else {
            $results['ksa_comprehensive'] = ['success' => false, 'error' => 'File not found', 'processed' => 0];
        }

        return $results;
    }

    /**
     * Detect language of text
     */
    private function detectLanguage(string $text): string
    {
        $hasArabic = preg_match('/[\x{0600}-\x{06FF}]/u', $text);
        $hasEnglish = preg_match('/[a-zA-Z]/', $text);

        if ($hasArabic && $hasEnglish) {
            return 'ar'; // Default to Arabic for mixed content since it's KSA-focused
        } elseif ($hasArabic) {
            return 'ar';
        } else {
            return 'en';
        }
    }
}
