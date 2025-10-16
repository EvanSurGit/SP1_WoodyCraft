<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $supported = config('locales.supported', ['fr','en']);
        $locale = session('locale');

        if (!$locale) {
            // Détection auto depuis le navigateur (parmi les langues supportées)
            $locale = $request->getPreferredLanguage($supported) ?? config('app.locale');
            session(['locale' => $locale]);
        }

        if (!in_array($locale, $supported)) {
            $locale = config('app.fallback_locale');
        }

        App::setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}
