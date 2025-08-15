<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\MultiStepClassMap;

class CheckFormStep
{
    public function handle(Request $request, Closure $next): Response
    {
        $currentRoute = $request->route()->getName();
        $event = $request->route('event');
        $stepClass = MultiStepClassMap::getClass($currentRoute);
        $inputs = session($stepClass::sessionInputName(), []);
        if ($currentRoute === $stepClass::endRoute()) {
            if (array_key_exists($stepClass::lastStepRouteName(), $inputs)
                    || (($inputs[$stepClass::firstStepRouteName()] 
                    ?? [])['consent'] ?? null) === '0') {
                return $next($request);
            } else {
                return redirect()->route($stepClass::startRoute(), [
                    'event' => $event->public_id
                ]);
            }
        }
        $step = new $stepClass($currentRoute);
        if ($step->isFirstStep()) return $next($request);
        return array_key_exists($step->previousStepRouteName(), $inputs) 
            ? $next($request)
            : redirect()->route($step::startRoute(), [
                'event' => $event->public_id
            ]);
    }
}
