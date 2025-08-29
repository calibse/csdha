<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Services\MultiStepClassMap;
use App\Http\Requests\StoreMultiStepFormResponseRequest;

class MultiStepFormController extends Controller
{
    public function createResponse(Request $request, Event $event)
    {
        $currentRoute = $request->route()->getName();
        $stepClass = MultiStepClassMap::getClass($currentRoute);
        $step = new $stepClass($currentRoute);
        $inputs = session($step::sessionInputName(), []);
        return view($step->view(), [
            'eventName' => $event->gpoaActivity->name,
            'event' => $event,
            'previousStepRoute' => $step->previousStepRouteName() 
                ? route($step->previousStepRouteName() . '.create', [
                    'event' => $event->public_id
                ]) : null,
            'lastStep' => $step->isLastStep(),
            'submitRoute' => route($step->routeName(),
                ['event' => $event->public_id]),
            'inputs' => $inputs[$step->routeName()] ?? []
        ] + $step::viewsData());
    }

    public function storeResponse(StoreMultiStepFormResponseRequest $request, 
            Event $event)
    {
        $currentRoute = $request->route()->getName();
        $stepClass = MultiStepClassMap::getClass($currentRoute);
        $step = new $stepClass($currentRoute);
        $inputs = session($step::sessionInputName(), []);
        $inputs[$step->routeName()] = $request->only($step->inputs());
        session([$step::sessionInputName() => $inputs]);
        if (($step->isFirstStep() && $inputs[$step->routeName()]['consent'] 
                === '0') || $step->isLastStep()) {
            return redirect()->route($step::endRoute(), [
                'event' => $event->public_id
            ]);
        }
        return redirect()->route($step->nextStepRouteName() . '.create', [
            'event' => $event->public_id
        ]);
    }

    public function end(Request $request, Event $event)
    {
        $currentRoute = $request->route()->getName();
        $stepClass = MultiStepClassMap::getClass($currentRoute);
        $stepClass::store($event);
        session()->forget($stepClass::sessionInputName());
        return view($stepClass::endView(), [
            'eventName' => $event->gpoaActivity->name,
            'event' => $event,
            'end' => true
        ] + $stepClass::endViewData());
    }
}
