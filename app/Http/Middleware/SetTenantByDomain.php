<?php

namespace App\Http\Middleware;

use App\Models\StudyProgram;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetTenantByDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = strtolower($request->getHost());
        $host = rtrim($host, '.');

        $tenant = StudyProgram::query()
            ->where('domain', $host)
            ->where('is_active', true)
            ->with('media')
            ->first();

        if (! $tenant && str_starts_with($host, 'www.')) {
            $tenant = StudyProgram::query()
                ->where('domain', substr($host, 4))
                ->where('is_active', true)
                ->with('media')
                ->first();
        }

        if (! $tenant) {
            abort(404);
        }

        $request->attributes->set('tenant', $tenant);
        View::share('tenant', $tenant);

        return $next($request);
    }
}
