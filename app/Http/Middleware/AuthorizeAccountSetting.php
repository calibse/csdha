<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class AuthorizeAccountSetting
{
    public function handle(Request $request, Closure $next, string $ability,
            string $modelName): Response
    {
        if (!Gate::allows($ability, $modelName)) {
            return redirect()->route('profile.edit');
        }
        return $next($request);
    }
}
