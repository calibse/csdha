<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Format;

class CheckEventRegisStep
{
    public function handle(Request $request, Closure $next): Response
    {
        $event = $request->route('event');
        $sessionDataName = 'event_registration';
        $inputs = session($sessionDataName, []);
        $routePrefix = 'events.registrations.';
        $steps = [
            $routePrefix . 'consent',
            $routePrefix . 'identity',
            $routePrefix . 'end'
        ];
        $resourceRoute = Format::getResourceRoute($request);
        $previousStep = $steps[array_search($resourceRoute, $steps) - 1];
        if (!array_key_exists($previousStep, $inputs)) {
            return redirect()->route('events.registrations.start', [
                'event' => $event->public_id
            ]);
        }
        return $next($request);
    }
}
