<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Gpoa;

class CheckGpoaActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $gpoa = Gpoa::active()->first();
        $activity = $request->route('activity');
        if ($gpoa && $activity && !$gpoa->activities()->whereKey($activity->id)
                ->exists()) {
            abort(404);
        }
        return $next($request);
    }
}
