<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\EvalFormStep;

class EnsureEachEvalFormStepIsComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $currentRoute = $request->route()->getName();
        $event = $request->route('event');
        $inputs = session('evalFormInputs', []);
        if ($currentRoute === 'events.eval-form.end') {
            if (array_key_exists(EvalFormStep::lastStepRouteName(), $inputs)
                || (($inputs[EvalFormStep::firstStepRouteName()] 
                        ?? [])['consent'] ?? null) 
                === '0') {
                return $next($request);
            } else {
                return redirect()->route('events.eval-form.create', [
                    'event' => $event->public_id
                ]);
            }
        }
        $step = new EvalFormStep($currentRoute);
        if ($step->isFirstStep()) return $next($request);
        return array_key_exists($step->previousStepRouteName(), $inputs) 
            ? $next($request)
            : redirect()->route('events.eval-form.create', [
                'event' => $event->public_id
            ]);
    }
}
