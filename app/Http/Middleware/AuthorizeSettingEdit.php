<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeSettingEdit
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user()->hasPerm('settings.edit')) {
            return redirect('/home.html');
        }

        return $next($request);
    }
}
