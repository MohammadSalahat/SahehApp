<?php

use App\Http\Controllers\Api\DatasetFakeNewsController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\VerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes (require authentication via API key middleware)
Route::middleware('api.key')->group(function () {

    // Python AI Service Routes (for Python to call Laravel)
    Route::prefix('datasets')->group(function () {
        Route::post('/fake-news', [DatasetFakeNewsController::class, 'store'])
            ->name('api.datasets.fake-news.store');

        Route::post('/fake-news/bulk', [DatasetFakeNewsController::class, 'bulkStore'])
            ->name('api.datasets.fake-news.bulk-store');

        Route::get('/fake-news', [DatasetFakeNewsController::class, 'index'])
            ->name('api.datasets.fake-news.index');

        Route::post('/fake-news/search', [DatasetFakeNewsController::class, 'search'])
            ->name('api.datasets.fake-news.search');
    });

    // Feedback routes
    Route::prefix('feedbacks')->group(function () {
        Route::post('/', [FeedbackController::class, 'store'])
            ->name('api.feedbacks.store');

        Route::get('/', [FeedbackController::class, 'index'])
            ->name('api.feedbacks.index');

        Route::get('/statistics', [FeedbackController::class, 'statistics'])
            ->name('api.feedbacks.statistics');
    });
});

// Verification routes (for frontend, authenticated users)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/verification/verify', [VerificationController::class, 'verify'])
        ->name('api.verification.verify');
});

// Health check (no authentication required)
Route::get('/health', [VerificationController::class, 'healthCheck'])
    ->name('api.health');
