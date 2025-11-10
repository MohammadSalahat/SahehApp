<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule weekly fake news dataset processing
// Processes LIAR and CredBank datasets with Arabic + KSA legal filters
// Runs every Sunday at 2:00 AM
Schedule::command('fakenews:process')
    ->weekly()
    ->sundays()
    ->at('02:00')
    ->timezone('Asia/Riyadh')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/dataset-processing.log'))
    ->onSuccess(function () {
        logger()->info('✅ Weekly fake news dataset processing completed successfully');
    })
    ->onFailure(function () {
        logger()->error('❌ Weekly fake news dataset processing failed');
    });

// Schedule daily KSA legal news fetching for larger datasets
// Fetches fresh legal news from KSA government sources (MOJ, SPA, Legal Portal, Courts)
// Runs daily at 2:00 AM for comprehensive data collection (target: 1000+ entries/month)
Schedule::command('news:fetch-ksa-legal')
    ->daily()
    ->at('02:00')
    ->timezone('Asia/Riyadh')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/ksa-legal-news.log'))
    ->onSuccess(function () {
        logger()->info('✅ Daily KSA legal news fetch completed successfully');
    })
    ->onFailure(function () {
        logger()->error('❌ Daily KSA legal news fetch failed');
        // You could add email notification here if needed
    });

// Schedule weekly comprehensive historical data fetch
// Fetches historical KSA legal news for building larger datasets
// Runs every Sunday at 3:00 AM to collect 60 days of historical data
Schedule::call(function () {
    $pythonScript = base_path('../SahehAIPython/scripts/enhanced_ksa_legal_news_fetcher.py');
    $command = 'cd '.base_path('../SahehAIPython')." && python3 {$pythonScript} --historical";
    $output = shell_exec($command.' 2>&1');
    logger()->info('Historical KSA legal news fetch output: '.$output);
})
    ->name('fetch-historical-ksa-legal-news')
    ->weekly()
    ->sundays()
    ->at('03:00')
    ->timezone('Asia/Riyadh')
    ->withoutOverlapping()
    ->onSuccess(function () {
        logger()->info('✅ Weekly historical KSA legal news fetch completed successfully');
    })
    ->onFailure(function () {
        logger()->error('❌ Weekly historical KSA legal news fetch failed');
    });
