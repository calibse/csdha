<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPerm
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, 
        string ...$permissions): Response
    {
        return $next($request);

        $routeParams = $request->route()->parameters();
        $itemId = end($routeParams);
        if (auth()->user()->hasPerm($permissions, $itemId))
            return $next($request);
        
        return redirect()->route('user.home');
    }
}
