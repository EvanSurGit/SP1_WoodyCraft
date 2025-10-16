<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(string $locale, Request $request)
    {
        $supported = config('locales.supported', ['fr','en']);
        if (!in_array($locale, $supported)) {
            $locale = config('app.fallback_locale');
        }
        session(['locale' => $locale]);

        // Retourne à la page courante
        return back();
    }
}
