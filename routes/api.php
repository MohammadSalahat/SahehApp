<?php

use App\Http\Controllers\Api\DatasetFakeNewsController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\SourceController;
use App\Http\Controllers\Api\UserController;
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
        // Fake News Dataset Routes
        Route::post('/fake-news', [DatasetFakeNewsController::class, 'store'])
            ->name('api.datasets.fake-news.store');

        Route::post('/fake-news/bulk', [DatasetFakeNewsController::class, 'bulkStore'])
            ->name('api.datasets.fake-news.bulk-store');

        Route::get('/fake-news', [DatasetFakeNewsController::class, 'index'])
            ->name('api.datasets.fake-news.index');

        Route::get('/fake-news/{id}', [DatasetFakeNewsController::class, 'show'])
            ->name('api.datasets.fake-news.show');

        Route::put('/fake-news/{id}', [DatasetFakeNewsController::class, 'update'])
            ->name('api.datasets.fake-news.update');

        Route::delete('/fake-news/{id}', [DatasetFakeNewsController::class, 'destroy'])
            ->name('api.datasets.fake-news.destroy');

        Route::post('/fake-news/search', [DatasetFakeNewsController::class, 'search'])
            ->name('api.datasets.fake-news.search');

        // Legitimate News Dataset Routes (for balanced training data)
        Route::post('/legitimate-news', [DatasetFakeNewsController::class, 'storeLegitimate'])
            ->name('api.datasets.legitimate-news.store');

        Route::post('/legitimate-news/bulk', [DatasetFakeNewsController::class, 'bulkStoreLegitimate'])
            ->name('api.datasets.legitimate-news.bulk-store');

        Route::get('/legitimate-news', [DatasetFakeNewsController::class, 'indexLegitimate'])
            ->name('api.datasets.legitimate-news.index');
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

    // Sources routes
    Route::prefix('sources')->group(function () {
        Route::get('/', [SourceController::class, 'index'])
            ->name('api.sources.index');

        Route::get('/trusted', [SourceController::class, 'trusted'])
            ->name('api.sources.trusted');

        Route::get('/{id}', [SourceController::class, 'show'])
            ->name('api.sources.show');

        Route::post('/', [SourceController::class, 'store'])
            ->name('api.sources.store');

        Route::put('/{id}', [SourceController::class, 'update'])
            ->name('api.sources.update');

        Route::delete('/{id}', [SourceController::class, 'destroy'])
            ->name('api.sources.destroy');
    });

    // User routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])
            ->name('api.users.index');

        Route::get('/statistics', [UserController::class, 'statistics'])
            ->name('api.users.statistics');

        Route::get('/{id}', [UserController::class, 'show'])
            ->name('api.users.show');

        Route::post('/', [UserController::class, 'store'])
            ->name('api.users.store');

        Route::put('/{id}', [UserController::class, 'update'])
            ->name('api.users.update');

        Route::delete('/{id}', [UserController::class, 'destroy'])
            ->name('api.users.destroy');
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
