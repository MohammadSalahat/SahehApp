<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class FetchKSALegalNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch-ksa-legal {--force : Force fetch even if run recently}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch latest KSA legal news from comprehensive sources (daily for large datasets)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🇸🇦 Starting KSA Legal News Weekly Fetch...');
        $this->info('📅 '.Carbon::now()->format('Y-m-d H:i:s'));
        $this->info('='.str_repeat('=', 60));

        try {
            // Check if we should run (weekly check)
            if (! $this->shouldRun() && ! $this->option('force')) {
                $this->info('⏭️  Skipping: Weekly fetch already completed recently');

                return Command::SUCCESS;
            }

            $pythonProjectPath = base_path('../SahehAIPython');

            // Validate Python project exists
            if (! is_dir($pythonProjectPath)) {
                $this->error("❌ Python project not found at: {$pythonProjectPath}");
                Log::error('KSA Legal News Fetch: Python project not found', ['path' => $pythonProjectPath]);

                return Command::FAILURE;
            }

            // Python script paths - Use enhanced fetcher for larger datasets
            $scriptPath = $pythonProjectPath.'/scripts/enhanced_ksa_legal_news_fetcher.py';
            $fallbackScript = $pythonProjectPath.'/scripts/fetch_ksa_legal_news_simple.py';

            // Use enhanced script if available, fallback to simple version
            if (! file_exists($scriptPath) && file_exists($fallbackScript)) {
                $scriptPath = $fallbackScript;
                $this->warn('⚠️  Using fallback simple fetcher');
            }

            $venvPython = $pythonProjectPath.'/venv/bin/python';

            // Use system python if venv doesn't exist
            $pythonExecutable = file_exists($venvPython) ? $venvPython : 'python3';

            $this->info("🐍 Using Python: {$pythonExecutable}");
            $this->info("📂 Python project: {$pythonProjectPath}");
            $this->info("📜 Enhanced Script: {$scriptPath}");

            // Add historical flag for first run or forced runs
            $scriptArgs = [$pythonExecutable, $scriptPath];
            if ($this->option('force') || ! cache('ksa_legal_news_last_run')) {
                $scriptArgs[] = '--historical';
                $this->info('🕒 Including historical data fetch for comprehensive dataset');
            }

            // Execute Python script
            $this->info('🚀 Executing enhanced KSA legal news fetcher...');

            $result = Process::path($pythonProjectPath)
                ->timeout(3600) // 60 minutes timeout for enhanced fetcher
                ->run($scriptArgs);

            // Process results
            if ($result->successful()) {
                $this->info('✅ KSA Legal News fetch completed successfully!');
                $this->line($result->output());

                // Log success
                Log::info('KSA Legal News Fetch completed successfully', [
                    'output' => $result->output(),
                    'execution_time' => Carbon::now(),
                ]);

                // Mark as completed
                $this->markAsCompleted();

                return Command::SUCCESS;
            } else {
                $this->error('❌ KSA Legal News fetch failed');
                $this->error($result->errorOutput());

                // Log error
                Log::error('KSA Legal News Fetch failed', [
                    'error' => $result->errorOutput(),
                    'exit_code' => $result->exitCode(),
                ]);

                return Command::FAILURE;
            }

        } catch (\Exception $e) {
            $this->error("❌ Unexpected error: {$e->getMessage()}");
            Log::error('KSA Legal News Fetch exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }

    /**
     * Check if we should run the daily fetch
     */
    private function shouldRun(): bool
    {
        $lastRun = cache('ksa_legal_news_last_run');

        if (! $lastRun) {
            return true; // Never run before
        }

        $lastRunDate = Carbon::parse($lastRun);
        $hoursSinceLastRun = $lastRunDate->diffInHours(Carbon::now());

        // Run if it's been more than 20 hours (allowing for flexible daily scheduling)
        return $hoursSinceLastRun >= 20;
    }

    /**
     * Mark the fetch as completed
     */
    private function markAsCompleted(): void
    {
        cache(['ksa_legal_news_last_run' => Carbon::now()->toISOString()], now()->addMonth());
        $this->info('📋 Marked daily fetch as completed');
    }
}
