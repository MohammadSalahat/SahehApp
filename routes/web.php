<?php

use App\Http\Controllers\Web\ContactRequestController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\VerificationController;
use App\Http\Middleware\LanguageMiddleware;
use App\Livewire\Pages\HomePage;
use App\Livewire\Pages\ReviewPage;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::controller(HomeController::class)->group(function () {
    Route::get('changeLanguage/{locale}', 'changeLanguage')->name('changeLanguage');
});

Route::middleware([LanguageMiddleware::class])->group(function () {

    Route::get('/', HomePage::class)->name('home');

    // Verification routes
    Route::post('/verify', [VerificationController::class, 'verify'])->name('verify');
    
    // Ultra-optimized verification routes (PRG pattern)
    Route::post('/verify-fast', [App\Http\Controllers\Web\OptimizedVerificationController::class, 'verify'])->name('verify.fast');
    Route::get('/verification-result/{id}', [App\Http\Controllers\Web\OptimizedVerificationController::class, 'showResult'])->name('verification.result');
    
    // Performance monitoring routes
    Route::get('/performance-stats', [App\Http\Controllers\Web\OptimizedVerificationController::class, 'getPerformanceStats'])->name('performance.stats')->middleware('auth');
    Route::get('/benchmark', [App\Http\Controllers\Web\OptimizedVerificationController::class, 'benchmark'])->name('benchmark')->middleware('auth');

    // Review route (requires auth)
    Route::get('/review', ReviewPage::class)->middleware('auth')->name('review');

    Route::view('dashboard', 'dashboard')
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::middleware('guest')->group(function () {
        Route::get('new-home', HomePage::class)->name('new.home');
        Route::get('new-contact-request', [ContactRequestController::class, 'create'])->name('new.contact.request');
    });

    Route::middleware(['auth'])->group(function () {
        Route::redirect('settings', 'settings/profile');

        Route::get('settings/profile', Profile::class)->name('settings.profile');
        Route::get('settings/password', Password::class)->name('settings.password');
        Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

        Route::get('settings/two-factor', TwoFactor::class)
            ->middleware(
                when(
                    Features::canManageTwoFactorAuthentication()
                        && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                    ['password.confirm'],
                    [],
                ),
            )
            ->name('two-factor.show');
    });

    require __DIR__.'/auth.php';
});
