<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    public function setLocale($lang)
    {
        // fetch all langs from helpers file
        if (in_array($lang, allLanguages())) {
            App::setLocale($lang);
            Session::put('locale', $lang);
        }

        return back();
    }
}
