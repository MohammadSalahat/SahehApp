<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class FetchLatestDatasets extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'datasets:fetch-latest 
                            {--source=all : Which source to fetch from (all, kaggle, github, huggingface)}
                            {--arabic : Focus on Arabic datasets}
                            {--limit=5 : Maximum number of datasets to fetch per source}';

    /**
     * The description of the console command.
     */
    protected $description = 'Fetch the latest fake news datasets from multiple sources';

    /**
     * Latest dataset sources
     */
    protected array $sources = [
        'github' => [
            // Recent GitHub repositories with fake news datasets
            'https://raw.githubusercontent.com/sumeetkr/AwesomeFakeNews/master/datasets/fake_or_real_news.csv',
            'https://raw.githubusercontent.com/joolsa/fake_real_news_dataset/master/fake_or_real_news.csv',
            'https://raw.githubusercontent.com/Apurva-tech/Fake-News-Detection/master/news.csv',
            'https://raw.githubusercontent.com/nishitpatel01/Fake_News_Detection/master/train.csv',
            'https://raw.githubusercontent.com/KennyWu/fake-news-classifier/master/fake_or_real_news.csv',
        ],

        'huggingface' => [
            // Hugging Face datasets API endpoints
            'https://huggingface.co/datasets/GonzaloA/fake_news/resolve/main/train.csv',
            'https://huggingface.co/datasets/ucirvine/fake-news/resolve/main/data.csv',
        ],

        'arabic_sources' => [
            // Arabic fake news datasets
            'https://raw.githubusercontent.com/arab-ai/AraBench/main/Arabic_Fake_News_Dataset/train.csv',
            'https://raw.githubusercontent.com/sabirdvd/Arabic-Fake-News-Detection/master/data/Arabic_dataset.csv',
            'https://raw.githubusercontent.com/MagedSaeed/Arabic-Fake-News-Detection/master/datasets/combined_dataset.csv',
            'https://raw.githubusercontent.com/AhmadAli2019/Arabic_Fake_News_Detection/main/ArFakeNews.csv',
        ],

        'recent_2024' => [
            // Recent 2024-2025 datasets
            'https://raw.githubusercontent.com/DataForScience/FakeNews/master/fake_or_real_news.csv',
            'https://raw.githubusercontent.com/sahil-rajput/fake-news-detection/main/WELFake_Dataset.csv',
            'https://raw.githubusercontent.com/JaradTurner/fake-news-nlp/main/news_articles.csv',
        ],
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔥 Fetching latest fake news datasets...');
        $this->newLine();

        $sourceChoice = $this->option('source');
        $arabicFocus = $this->option('arabic');
        $limit = (int) $this->option('limit');

        if ($arabicFocus) {
            $this->info('🌍 Focusing on Arabic datasets...');
            $sources = ['arabic_sources'];
        } elseif ($sourceChoice === 'all') {
            $sources = array_keys($this->sources);
        } else {
            $sources = [$sourceChoice];
        }

        $totalDownloaded = 0;

        foreach ($sources as $sourceKey) {
            if (! isset($this->sources[$sourceKey])) {
                $this->error("❌ Unknown source: {$sourceKey}");

                continue;
            }

            $this->info('📡 Fetching from: '.ucfirst($sourceKey));
            $downloaded = $this->fetchFromSource($sourceKey, $limit);
            $totalDownloaded += $downloaded;
            $this->newLine();
        }

        $this->info("🎉 Downloaded {$totalDownloaded} new datasets!");

        // Run processing command if datasets were downloaded
        if ($totalDownloaded > 0) {
            $this->info('🔄 Processing downloaded datasets...');
            $this->call('fakenews:process', ['--dataset' => 'all']);
        }

        return Command::SUCCESS;
    }

    /**
     * Fetch datasets from a specific source
     */
    protected function fetchFromSource(string $sourceKey, int $limit): int
    {
        $urls = $this->sources[$sourceKey];
        $downloaded = 0;
        $processed = 0;

        foreach ($urls as $url) {
            if ($processed >= $limit) {
                $this->line("⏹️  Reached limit of {$limit} datasets for {$sourceKey}");
                break;
            }

            $processed++;

            if ($this->downloadDataset($url, $sourceKey)) {
                $downloaded++;
            }
        }

        return $downloaded;
    }

    /**
     * Download a single dataset
     */
    protected function downloadDataset(string $url, string $source): bool
    {
        $filename = $this->generateFilename($url, $source);
        $directory = storage_path("app/datasets/latest/{$source}");

        // Create directory if it doesn't exist
        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $localPath = "{$directory}/{$filename}";

        // Skip if file already exists and is not empty
        if (File::exists($localPath) && File::size($localPath) > 1000) {
            $this->line("⏭️  Already exists: {$filename}");

            return false;
        }

        $this->line("⬇️  Downloading: {$filename}");
        $this->line("   From: {$url}");

        try {
            // Test if URL is accessible
            $headResponse = Http::timeout(10)->head($url);

            if (! $headResponse->successful()) {
                // Try direct GET request as fallback
                $testResponse = Http::timeout(10)->get($url);
                if (! $testResponse->successful()) {
                    $this->warn("⚠️  URL not accessible: {$url}");

                    return false;
                }
            }

            // Download the file
            $response = Http::timeout(300)->get($url);

            if ($response->successful()) {
                $content = $response->body();

                // Basic validation - check if it looks like CSV/dataset
                if (strlen($content) < 100) {
                    $this->warn("⚠️  Downloaded content too small: {$filename}");

                    return false;
                }

                File::put($localPath, $content);
                $size = $this->formatBytes(File::size($localPath));

                // Validate CSV structure
                $isValid = $this->validateCsvStructure($localPath);

                if ($isValid) {
                    $this->info("✅ Downloaded and validated: {$filename} ({$size})");

                    // Copy to main datasets directory for processing
                    $mainPath = storage_path("app/datasets/latest_{$source}_{$filename}");
                    File::copy($localPath, $mainPath);

                    return true;
                } else {
                    $this->warn("⚠️  Invalid CSV structure: {$filename}");
                    File::delete($localPath);

                    return false;
                }

            } else {
                $this->error("❌ Failed to download: {$url}");

                return false;
            }

        } catch (Exception $e) {
            $this->error("❌ Error downloading {$url}: ".$e->getMessage());

            return false;
        }
    }

    /**
     * Generate filename from URL
     */
    protected function generateFilename(string $url, string $source): string
    {
        $urlPath = parse_url($url, PHP_URL_PATH);
        $pathInfo = pathinfo($urlPath);

        $filename = $pathInfo['filename'] ?? 'dataset';
        $extension = $pathInfo['extension'] ?? 'csv';

        // Add timestamp and source to make filename unique
        $timestamp = date('Y-m-d_H-i');

        return "{$source}_{$filename}_{$timestamp}.{$extension}";
    }

    /**
     * Validate CSV structure
     */
    protected function validateCsvStructure(string $filePath): bool
    {
        try {
            $handle = fopen($filePath, 'r');
            if (! $handle) {
                return false;
            }

            // Read header
            $header = fgetcsv($handle);
            if (! $header || count($header) < 2) {
                fclose($handle);

                return false;
            }

            // Check if header contains common fake news dataset columns
            $headerString = strtolower(implode(',', $header));
            $requiredPatterns = ['title', 'text', 'content', 'news', 'article', 'label', 'fake', 'real', 'class'];

            $hasRelevantColumn = false;
            foreach ($requiredPatterns as $pattern) {
                if (strpos($headerString, $pattern) !== false) {
                    $hasRelevantColumn = true;
                    break;
                }
            }

            if (! $hasRelevantColumn) {
                fclose($handle);

                return false;
            }

            // Read a few rows to validate structure
            $rowCount = 0;
            while (($row = fgetcsv($handle)) !== false && $rowCount < 5) {
                if (count($row) != count($header)) {
                    fclose($handle);

                    return false;
                }
                $rowCount++;
            }

            fclose($handle);

            return $rowCount > 0;

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Format bytes to human readable format
     */
    protected function formatBytes(int $size): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2).' '.$units[$unitIndex];
    }
}
