<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;

class EnsureCartToken
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->cookie('cart_token')) {
            Cookie::queue('cart_token', (string) Str::uuid(), 60*24*30); // 30 jours
        }
        return $next($request);
    }
}

