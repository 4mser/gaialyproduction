<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = config('app.locale');

        // get Locale by auth user
        if (auth()->check()) {
            $locale = auth()->user()->locale;
            session()->put('locale', $locale);
        }
        // Set locale
        app()->setLocale($locale);
        return $next($request);
    }
}
