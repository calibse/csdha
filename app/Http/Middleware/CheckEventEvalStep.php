<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Format;

class CheckEventEvalStep
{
    public function handle(Request $request, Closure $next): Response
    {
        $event = $request->route('event');
        $sessionDataName = 'event_evaluation';
        $inputs = session($sessionDataName, []);
        $routePrefix = 'events.evaluations.';
        $steps = [
            $routePrefix . 'consent',
            $routePrefix . 'evaluation',
            $routePrefix . 'acknowledgement',
            $routePrefix . 'end'
        ];
        $resourceRoute = Format::getResourceRoute($request);
        $previousStep = $steps[array_search($resourceRoute, $steps) - 1];
        if (!array_key_exists($previousStep, $inputs)) {
            return redirect()->route('events.evaluations.consent.edit', [
                'event' => $event->public_id,
                'token' => $request->token ?? null
            ]);
        }
        return $next($request);
    }
}
