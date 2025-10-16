<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class AuthorizeSetting
{
    public function handle(Request $request, Closure $next, string $ability,
            string $modelName): Response
    {
        $model = class_exists($modelName) ? $modelName 
            : $request->route($modelName);
        if (!Gate::allows($ability, $model)) {
            return redirect()->route('settings.index');
        }
        return $next($request);
    }
}
