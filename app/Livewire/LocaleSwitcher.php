<?php

namespace App\Livewire;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class LocaleSwitcher extends Component
{
    public $currentLocale;

    public $availableLocales;

    public function mount()
    {
        $this->currentLocale = App::getLocale();
        $this->availableLocales = config('app.available_locales');
    }

    public function switchLocale($locale)
    {
        // Validate locale
        if (! array_key_exists($locale, config('app.available_locales'))) {
            return;
        }

        // Store in session
        Session::put('locale', $locale);

        // Set application locale
        App::setLocale($locale);

        // Update current locale
        $this->currentLocale = $locale;

        // Refresh the page to apply locale changes
        return Redirect::back();
    }

    public function render()
    {
        return view('livewire.locale-switcher');
    }
}
