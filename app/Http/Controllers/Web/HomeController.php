<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function changeLanguage($locale): RedirectResponse
    {
        App::setLocale(strtolower($locale));
        Session::put('locale', strtolower($locale));

        return Redirect::back();
    }
}
