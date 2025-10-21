<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Gpoa;

class EnsureGpoaIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Gpoa::active()->exists()) {
            abort(404);
        }
        return $next($request);
    }
}
