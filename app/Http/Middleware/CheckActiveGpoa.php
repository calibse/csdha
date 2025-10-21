<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Gpoa;

class CheckActiveGpoa
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Gpoa::active()->exists()) {
            return redirect('/home.html');
        }
        return $next($request);
    }
}
