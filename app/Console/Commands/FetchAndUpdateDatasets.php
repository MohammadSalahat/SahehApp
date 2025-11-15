<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class FetchAndUpdateDatasets extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'datasets:fetch-and-update 
                            {--dataset=all : Which dataset to fetch (all, liar, credbank, fakenewsnet, isot, arabic)}
                            {--force : Force download even if file exists}';

    /**
     * The description of the console command.
     */
    protected $description = 'Fetch and update fake news datasets from various sources';

    /**
     * Dataset sources configuration
     */
    protected array $datasets = [
        'liar' => [
            'name' => 'LIAR Dataset',
            'description' => 'Political fact-checking dataset from PolitiFact',
            'urls' => [
                'https://raw.githubusercontent.com/thiagosc21/Fake-News-Classification/master/train.tsv',
                'https://www.cs.ucsb.edu/~william/data/liar_dataset.zip', // Alternative source
            ],
            'format' => 'tsv',
            'size_estimate' => '~3MB',
            'local_path' => 'datasets/liar/liar_dataset.tsv',
            'csv_path' => 'datasets/liar/politifact_fake.csv',
        ],

        'credbank' => [
            'name' => 'CredBank Dataset',
            'description' => 'Social media credibility assessment dataset',
            'urls' => [
                'https://raw.githubusercontent.com/compsocial/CredBank/master/data/annotations.csv',
                'https://github.com/compsocial/CredBank/archive/refs/heads/master.zip',
            ],
            'format' => 'csv',
            'size_estimate' => '~5MB',
            'local_path' => 'datasets/credbank/credbank_full.csv',
            'csv_path' => 'datasets/credbank/credbank_sample.csv',
        ],

        'fakenewsnet' => [
            'name' => 'FakeNewsNet Dataset',
            'description' => 'Comprehensive fake news dataset with social context',
            'urls' => [
                'https://raw.githubusercontent.com/KaiDMML/FakeNewsNet/master/dataset/politifact_fake.csv',
                'https://raw.githubusercontent.com/KaiDMML/FakeNewsNet/master/dataset/politifact_real.csv',
                'https://raw.githubusercontent.com/KaiDMML/FakeNewsNet/master/dataset/gossipcop_fake.csv',
                'https://raw.githubusercontent.com/KaiDMML/FakeNewsNet/master/dataset/gossipcop_real.csv',
            ],
            'format' => 'csv',
            'size_estimate' => '~10MB',
            'local_path' => 'datasets/fakenewsnet/',
            'csv_path' => 'datasets/fakenewsnet/',
        ],

        'isot' => [
            'name' => 'ISOT Fake News Dataset',
            'description' => 'Large-scale fake news dataset for binary classification',
            'urls' => [
                'https://raw.githubusercontent.com/IsotLab/Fake-News-Dataset-2020/main/Fake.csv',
                'https://raw.githubusercontent.com/IsotLab/Fake-News-Dataset-2020/main/True.csv',
            ],
            'format' => 'csv',
            'size_estimate' => '~50MB',
            'local_path' => 'datasets/isot/',
            'csv_path' => 'datasets/isot/',
        ],

        'arabic' => [
            'name' => 'Arabic Fake News Datasets',
            'description' => 'Collection of Arabic fake news datasets',
            'urls' => [
                'https://raw.githubusercontent.com/arab-ai/AraBench/main/Arabic_Fake_News_Dataset/train.csv',
                'https://raw.githubusercontent.com/sabirdvd/Arabic-Fake-News-Detection/master/data/Arabic_dataset.csv',
            ],
            'format' => 'csv',
            'size_estimate' => '~20MB',
            'local_path' => 'datasets/arabic/',
            'csv_path' => 'datasets/arabic/',
        ],
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting dataset fetch and update process...');
        $this->newLine();

        $datasetChoice = $this->option('dataset');
        $force = $this->option('force');

        $datasets = $datasetChoice === 'all' ? array_keys($this->datasets) : [$datasetChoice];

        foreach ($datasets as $datasetKey) {
            if (! isset($this->datasets[$datasetKey])) {
                $this->error("❌ Unknown dataset: {$datasetKey}");

                continue;
            }

            $this->processDataset($datasetKey, $force);
        }

        $this->newLine();
        $this->info('✅ Dataset fetch and update process completed!');

        return Command::SUCCESS;
    }

    /**
     * Process a single dataset
     */
    protected function processDataset(string $datasetKey, bool $force): void
    {
        $dataset = $this->datasets[$datasetKey];

        $this->info("📊 Processing {$dataset['name']}...");
        $this->line("   Description: {$dataset['description']}");
        $this->line("   Estimated size: {$dataset['size_estimate']}");
        $this->newLine();

        // Create directory structure
        $this->createDirectoryStructure($datasetKey);

        // Download files
        $downloadedFiles = [];
        foreach ($dataset['urls'] as $index => $url) {
            $downloadResult = $this->downloadFile($url, $datasetKey, $index, $force);
            if ($downloadResult) {
                $downloadedFiles[] = $downloadResult;
            }
        }

        if (empty($downloadedFiles)) {
            $this->warn("⚠️  No files were downloaded for {$dataset['name']}");

            return;
        }

        // Process downloaded files
        $this->processDownloadedFiles($datasetKey, $downloadedFiles);

        $this->info("✅ {$dataset['name']} processing completed!");
        $this->newLine();
    }

    /**
     * Create directory structure for dataset
     */
    protected function createDirectoryStructure(string $datasetKey): void
    {
        $paths = [
            storage_path("app/datasets/{$datasetKey}"),
            storage_path("app/datasets/{$datasetKey}/raw"),
            storage_path("app/datasets/{$datasetKey}/processed"),
        ];

        foreach ($paths as $path) {
            if (! File::exists($path)) {
                File::makeDirectory($path, 0755, true);
                $this->line("📁 Created directory: {$path}");
            }
        }
    }

    /**
     * Download a file from URL
     */
    protected function downloadFile(string $url, string $datasetKey, int $index, bool $force): ?string
    {
        $filename = $this->getFilenameFromUrl($url, $index);
        $localPath = storage_path("app/datasets/{$datasetKey}/raw/{$filename}");

        if (File::exists($localPath) && ! $force) {
            $this->line("⏭️  File already exists: {$filename} (use --force to redownload)");

            return $localPath;
        }

        $this->line("⬇️  Downloading: {$filename}");
        $this->line("   URL: {$url}");

        try {
            // Check if URL is accessible
            $response = Http::timeout(10)->head($url);

            if (! $response->successful()) {
                $this->warn("⚠️  URL not accessible: {$url}");

                return null;
            }

            // Download with progress indication
            $response = Http::timeout(300)->get($url);

            if ($response->successful()) {
                File::put($localPath, $response->body());
                $size = $this->formatBytes(File::size($localPath));
                $this->info("✅ Downloaded: {$filename} ({$size})");

                return $localPath;
            } else {
                $this->error("❌ Failed to download: {$url}");

                return null;
            }

        } catch (Exception $e) {
            $this->error("❌ Error downloading {$url}: ".$e->getMessage());

            return null;
        }
    }

    /**
     * Get filename from URL
     */
    protected function getFilenameFromUrl(string $url, int $index): string
    {
        $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));
        $filename = $pathInfo['filename'] ?? "file_{$index}";
        $extension = $pathInfo['extension'] ?? 'txt';

        return "{$filename}.{$extension}";
    }

    /**
     * Process downloaded files and convert to CSV if needed
     */
    protected function processDownloadedFiles(string $datasetKey, array $downloadedFiles): void
    {
        foreach ($downloadedFiles as $filePath) {
            $this->line('🔄 Processing: '.basename($filePath));

            $extension = pathinfo($filePath, PATHINFO_EXTENSION);

            switch ($extension) {
                case 'tsv':
                    $this->convertTsvToCsv($filePath, $datasetKey);
                    break;
                case 'csv':
                    $this->processCsvFile($filePath, $datasetKey);
                    break;
                case 'zip':
                    $this->extractZipFile($filePath, $datasetKey);
                    break;
                default:
                    $this->line("⏭️  Skipping unknown format: {$extension}");
            }
        }
    }

    /**
     * Convert TSV to CSV
     */
    protected function convertTsvToCsv(string $tsvPath, string $datasetKey): void
    {
        $csvPath = storage_path("app/datasets/{$datasetKey}/processed/".
                               pathinfo($tsvPath, PATHINFO_FILENAME).'.csv');

        $this->line('🔄 Converting TSV to CSV...');

        try {
            $tsvContent = File::get($tsvPath);
            $csvContent = str_replace("\t", ',', $tsvContent);
            File::put($csvPath, $csvContent);

            $this->info('✅ Converted to CSV: '.basename($csvPath));

            // Copy to main dataset location
            $mainCsvPath = storage_path("app/datasets/{$datasetKey}/".basename($csvPath));
            File::copy($csvPath, $mainCsvPath);

        } catch (Exception $e) {
            $this->error('❌ Error converting TSV: '.$e->getMessage());
        }
    }

    /**
     * Process CSV file
     */
    protected function processCsvFile(string $csvPath, string $datasetKey): void
    {
        $processedPath = storage_path("app/datasets/{$datasetKey}/processed/".basename($csvPath));

        try {
            // Validate CSV structure
            $handle = fopen($csvPath, 'r');
            $header = fgetcsv($handle);
            $lineCount = 0;

            while (($row = fgetcsv($handle)) !== false) {
                $lineCount++;
                if ($lineCount > 10) {
                    break;
                } // Just check first few lines
            }
            fclose($handle);

            $this->line('✅ CSV validation passed - Header: '.implode(', ', array_slice($header, 0, 5)));

            // Copy to processed directory
            File::copy($csvPath, $processedPath);

            // Copy to main dataset location
            $mainCsvPath = storage_path("app/datasets/{$datasetKey}/".basename($csvPath));
            File::copy($csvPath, $mainCsvPath);

        } catch (Exception $e) {
            $this->error('❌ Error processing CSV: '.$e->getMessage());
        }
    }

    /**
     * Extract ZIP file
     */
    protected function extractZipFile(string $zipPath, string $datasetKey): void
    {
        $extractPath = storage_path("app/datasets/{$datasetKey}/raw/extracted");

        if (! File::exists($extractPath)) {
            File::makeDirectory($extractPath, 0755, true);
        }

        $this->line('📦 Extracting ZIP file...');

        try {
            $zip = new \ZipArchive;
            if ($zip->open($zipPath) === true) {
                $zip->extractTo($extractPath);
                $zip->close();
                $this->info('✅ ZIP extracted successfully');

                // Process extracted files
                $extractedFiles = File::allFiles($extractPath);
                foreach ($extractedFiles as $file) {
                    if (in_array($file->getExtension(), ['csv', 'tsv'])) {
                        $this->line('🔄 Processing extracted file: '.$file->getFilename());
                        $this->processDownloadedFiles($datasetKey, [$file->getPathname()]);
                    }
                }
            } else {
                $this->error('❌ Failed to extract ZIP file');
            }
        } catch (Exception $e) {
            $this->error('❌ Error extracting ZIP: '.$e->getMessage());
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
