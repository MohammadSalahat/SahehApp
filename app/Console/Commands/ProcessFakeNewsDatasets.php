<?php

namespace App\Console\Commands;

use App\Services\DatasetProcessorService;
use Illuminate\Console\Command;

class ProcessFakeNewsDatasets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fakenews:process
                            {--dataset= : Specific dataset to process (liar, credbank, fake_real_news, ksa_comprehensive, or all)}
                            {--no-arabic-filter : Disable Arabic text filtering}
                            {--no-ksa-filter : Disable KSA legal content filtering}
                            {--include-english : Include English content even with Arabic filter enabled}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process fake news datasets from multiple sources with Arabic and KSA legal filtering';

    /**
     * Dataset processor service
     */
    protected DatasetProcessorService $processor;

    /**
     * Create a new command instance.
     */
    public function __construct(DatasetProcessorService $processor)
    {
        parent::__construct();
        $this->processor = $processor;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting fake news dataset processing...');
        $this->newLine();

        $arabicFilter = ! $this->option('no-arabic-filter');
        $ksaFilter = ! $this->option('no-ksa-filter');
        $dataset = $this->option('dataset') ?? 'all';

        $this->info('Filters:');
        $this->line('  - Arabic text only: '.($arabicFilter ? 'YES' : 'NO'));
        $this->line('  - KSA legal only: '.($ksaFilter ? 'YES' : 'NO'));
        $this->newLine();

        $results = [];

        // Process specific dataset or all
        $validDatasets = ['liar', 'credbank', 'fake_real_news', 'ksa_comprehensive', 'all'];

        if ($dataset === 'liar' || $dataset === 'all') {
            $this->processLiar($arabicFilter, $ksaFilter);
        }

        if ($dataset === 'credbank' || $dataset === 'all') {
            $this->processCredBank($arabicFilter, $ksaFilter);
        }

        if ($dataset === 'fake_real_news' || $dataset === 'all') {
            $this->processFakeRealNews($arabicFilter, $ksaFilter);
        }

        if ($dataset === 'ksa_comprehensive' || $dataset === 'all') {
            $this->processKSAComprehensive($arabicFilter, $ksaFilter);
        }

        if (! in_array($dataset, $validDatasets)) {
            $this->error('Invalid dataset option. Use: liar, credbank, fake_real_news, ksa_comprehensive, or all');

            return self::FAILURE;
        }

        $this->newLine();
        $this->info('✅ Dataset processing completed!');

        return self::SUCCESS;
    }

    /**
     * Process LIAR dataset
     */
    private function processLiar(bool $arabicFilter, bool $ksaFilter): void
    {
        $this->info('📊 Processing LIAR Dataset...');

        $liarPath = storage_path('app/datasets/liar/politifact_fake.csv');

        if (! file_exists($liarPath)) {
            $this->warn("⚠️  LIAR dataset file not found at: {$liarPath}");
            $this->line('   Please place the dataset CSV file in: storage/app/datasets/liar/');

            return;
        }

        $bar = $this->output->createProgressBar();
        $bar->start();

        $result = $this->processor->processLiarDataset(
            $liarPath,
            $arabicFilter,
            $ksaFilter
        );

        $bar->finish();
        $this->newLine(2);

        if ($result['success']) {
            $this->displayResults('LIAR', $result);
        } else {
            $this->error('❌ LIAR processing failed: '.($result['error'] ?? 'Unknown error'));
        }
    }

    /**
     * Process CredBank dataset
     */
    private function processCredBank(bool $arabicFilter, bool $ksaFilter): void
    {
        $this->info('📊 Processing CredBank Dataset...');

        $credBankPath = storage_path('app/datasets/credbank/credbank_sample.csv');

        if (! file_exists($credBankPath)) {
            $this->warn("⚠️  CredBank dataset file not found at: {$credBankPath}");
            $this->line('   Please place the dataset CSV file in: storage/app/datasets/credbank/');

            return;
        }

        $bar = $this->output->createProgressBar();
        $bar->start();

        $result = $this->processor->processCredBankDataset(
            $credBankPath,
            $arabicFilter,
            $ksaFilter
        );

        $bar->finish();
        $this->newLine(2);

        if ($result['success']) {
            $this->displayResults('CredBank', $result);
        } else {
            $this->error('❌ CredBank processing failed: '.($result['error'] ?? 'Unknown error'));
        }
    }

    /**
     * Display processing results
     */
    private function displayResults(string $datasetName, array $result): void
    {
        $this->info("✅ {$datasetName} Results:");

        $this->table(
            ['Metric', 'Count'],
            [
                ['Total rows processed', number_format($result['total_rows'] ?? 0)],
                ['✅ Successfully imported', $this->formatSuccess($result['processed'] ?? 0)],
                ['🚫 Filtered (not Arabic)', number_format($result['filtered_not_arabic'] ?? 0)],
                ['🚫 Filtered (not KSA legal)', number_format($result['filtered_not_ksa'] ?? 0)],
                ['⏭️  Skipped (too short)', number_format($result['skipped_too_short'] ?? 0)],
                ['⏭️  Skipped (duplicate)', number_format($result['skipped_duplicate'] ?? 0)],
            ]
        );

        if (($result['processed'] ?? 0) === 0) {
            $this->newLine();
            $this->warn('⚠️  No records were imported!');
            $this->line('   This is expected because LIAR and CredBank are primarily English datasets.');
            $this->line('   Consider creating a custom Arabic KSA legal fake news dataset.');
        }
    }

    /**
     * Process Fake Real News dataset
     */
    private function processFakeRealNews(bool $arabicFilter, bool $ksaFilter): void
    {
        $this->info('📊 Processing Fake Real News Dataset...');

        $fakeRealNewsPath = storage_path('app/datasets/fake_real_news_kaggle.csv');

        if (! file_exists($fakeRealNewsPath)) {
            $this->warn("⚠️  Fake Real News dataset file not found at: {$fakeRealNewsPath}");
            $this->line('   Please run: php artisan datasets:fetch-latest to download it');

            return;
        }

        $this->line('   📁 Processing: '.basename($fakeRealNewsPath));
        $this->line('   📊 Records: ~166K fake/real news articles');

        $bar = $this->output->createProgressBar();
        $bar->start();

        $result = $this->processor->processFakeRealNewsDataset(
            $fakeRealNewsPath,
            $arabicFilter,
            $ksaFilter
        );

        $bar->finish();
        $this->newLine(2);

        if ($result['success']) {
            $this->displayResults('Fake Real News', $result);
        } else {
            $this->error("❌ Processing failed: {$result['error']}");
        }
    }

    /**
     * Process KSA Comprehensive dataset
     */
    private function processKSAComprehensive(bool $arabicFilter, bool $ksaFilter): void
    {
        $this->info('🇸🇦 Processing KSA Comprehensive Dataset...');

        $ksaPath = storage_path('app/datasets/ksa_comprehensive.csv');

        if (! file_exists($ksaPath)) {
            $this->warn("⚠️  KSA comprehensive dataset file not found at: {$ksaPath}");
            $this->line('   Please run: php artisan datasets:fetch-ksa to download it');
            $this->newLine();

            // Try to create it by running the fetch command
            $this->info('🔄 Attempting to fetch KSA datasets...');
            $this->call('datasets:fetch-ksa', ['--limit' => 5]);

            if (! file_exists($ksaPath)) {
                $this->error('❌ Could not create KSA dataset');

                return;
            }
        }

        $this->line('   📁 Processing: '.basename($ksaPath));
        $this->line('   🇸🇦 KSA-specific fake news detection dataset');

        $bar = $this->output->createProgressBar();
        $bar->start();

        $result = $this->processor->processKSADataset(
            $ksaPath,
            $arabicFilter,
            $ksaFilter
        );

        $bar->finish();
        $this->newLine(2);

        if ($result['success']) {
            $this->displayResults('KSA Comprehensive', $result);

            if ($result['imported'] > 0) {
                $this->info('🎉 KSA dataset successfully processed!');
                $this->line('   ✅ This dataset is specifically curated for Saudi Arabian fake news detection');
                $this->line('   🔍 Content includes legal, governmental, and social media misinformation');
            }
        } else {
            $this->error("❌ Processing failed: {$result['error']}");
        }
    }

    /**
     * Format success count with color
     */
    private function formatSuccess(int $count): string
    {
        if ($count === 0) {
            return "<fg=red>{$count}</>";
        } elseif ($count < 10) {
            return "<fg=yellow>{$count}</>";
        } else {
            return "<fg=green>{$count}</>";
        }
    }
}
