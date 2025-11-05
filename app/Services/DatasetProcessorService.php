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
     * Process all configured datasets
     */
    public function processAllDatasets(): array
    {
        $results = [];

        $liarPath = storage_path('app/datasets/liar/politifact_fake.csv');
        $credBankPath = storage_path('app/datasets/credbank/credbank_sample.csv');

        if (file_exists($liarPath)) {
            $results['liar'] = $this->processLiarDataset($liarPath);
        } else {
            $results['liar'] = ['success' => false, 'error' => 'File not found', 'processed' => 0];
        }

        if (file_exists($credBankPath)) {
            $results['credbank'] = $this->processCredBankDataset($credBankPath);
        } else {
            $results['credbank'] = ['success' => false, 'error' => 'File not found', 'processed' => 0];
        }

        return $results;
    }
}
