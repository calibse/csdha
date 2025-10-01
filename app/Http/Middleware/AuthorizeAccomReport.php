<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class AuthorizeAccomReport
{
    public function handle(Request $request, Closure $next, string $ability,
            string $modelName): Response
    {
        $model = $request->route($modelName);
        if (!Gate::allows($ability, $model)) {
            return redirect()->route('accom-reports.index');
        }
        return $next($request);
    }
}
