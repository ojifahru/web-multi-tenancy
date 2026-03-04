<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale') ?? session('locale') ?? config('app.locale');

        // Validate locale is supported
        if (! in_array($locale, ['en', 'id'])) {
            $locale = config('app.locale');
        }

        // Set the locale for this request
        App::setLocale($locale);

        // Store locale in session for persistence
        session(['locale' => $locale]);

        return $next($request);
    }
}
