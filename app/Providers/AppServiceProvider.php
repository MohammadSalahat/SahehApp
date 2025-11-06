<?php

namespace App\Providers;

use App\Enums\RequestStatus;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Filament Macros
        Tab::macro('excludeAttributeValue', function ($attribute = 'status', $excludedValue = RequestStatus::Archived->value) {
            $this->query(fn ($query) => $query->whereNot($attribute, $excludedValue));

            return $this;
        });
    }
}
