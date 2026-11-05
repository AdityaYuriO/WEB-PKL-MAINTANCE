<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRememberedLogin
{
    public function handle($request, Closure $next)
    {
        // Kalau belum login, tapi ada cookie remember
        if (!Auth::check() && Auth::viaRemember()) {
            // Paksa login dari cookie remember
            Auth::login(Auth::user(), true);
        }

        return $next($request);
    }
}
