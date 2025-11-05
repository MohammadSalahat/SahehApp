<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class FetchFakeNewsFromDatasets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch-datasets {--limit=50 : Number of records to fetch per dataset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch fake news from datasets (LIAR, CredBank) via Python service and store in MySQL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting fake news fetch from datasets...');

        $limit = $this->option('limit');
        $pythonProjectPath = base_path('../SahehAIPython');

        // Check if Python project exists
        if (!is_dir($pythonProjectPath)) {
            $this->error("❌ Python project not found at: {$pythonProjectPath}");
            return Command::FAILURE;
        }

        $this->info("📂 Python project path: {$pythonProjectPath}");

        // Path to the Python script
        $scriptPath = $pythonProjectPath . '/scripts/fetch_datasets.py';
        $venvPath = $pythonProjectPath . '/.venv/bin/python';

        // Check if script exists
        if (!file_exists($scriptPath)) {
            $this->warn("⚠️  Python script not found at: {$scriptPath}");
            $this->info("📝 The script will be created in the next step.");
        }

        // Execute Python script
        $this->info("🐍 Executing Python script to fetch datasets...");
        $this->newLine();

        $command = "cd {$pythonProjectPath} && {$venvPath} {$scriptPath} --limit={$limit}";

        try {
            $result = Process::path($pythonProjectPath)
                ->timeout(600) // 10 minutes timeout
                ->run("{$venvPath} {$scriptPath} --limit={$limit}");

            if ($result->successful()) {
                $this->info("✅ Python script executed successfully!");
                $this->newLine();
                $this->line($result->output());

                return Command::SUCCESS;
            } else {
                $this->error("❌ Python script failed!");
                $this->error($result->errorOutput());

                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error("❌ Error executing Python script: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
