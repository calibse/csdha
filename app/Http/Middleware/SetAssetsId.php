<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class SetAssetsId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (is_null(Cache::get('website_logo_id'))) {
            Cache::put('website_logo_id', Str::random(8));
        }
        if (is_null(Cache::get('organization_logo_id'))) {
            Cache::put('organization_logo_id', Str::random(8));
        }
        if (is_null(Cache::get('university_logo_id'))) {
            Cache::put('university_logo_id', Str::random(8));
        }
        return $next($request);
    }
}
