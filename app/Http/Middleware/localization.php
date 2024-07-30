<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Localization
{
    /**
     * Handle an incoming request by setting the application's locale.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $language = $request->session()->get('language', 'ar');
        if (in_array($language, ['en', 'ar', 'es'])) { // Example supported languages
            app()->setLocale($language);
        } else {
            app()->setLocale('ar'); // Default to Arabic if unsupported language
        }

        return $next($request);
    }
}