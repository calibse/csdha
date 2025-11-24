<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEventRegistration
{
    public function handle(Request $request, Closure $next): Response
    {
        $event = $request->route('event');
        if ($event->is_completed) {
            return response()->view('message', [
                'message' => 'This event ended.'
            ]);
        } 
        if (!$event->automatic_attendance) {
            abort(404);
	}
        return $next($request);
    }
}
