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
