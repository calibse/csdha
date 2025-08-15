<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateEventRegisFormRequest;
use App\Models\Event;
use App\Models\EventRegisForm;

class EventRegisFormController extends Controller
{
    public function edit(Event $event)
    {
        return view('events.edit-regis-form', [
            'event' => $event,
            'regisForm' => $event?->regisForm,
            'backRoute' => route('events.edit', ['event' => $event->public_id]),
            'formAction' => route('events.regis-form.update', [
                'event' => $event->public_id
            ])
        ]);
    }

    public function update(UpdateEventRegisFormRequest $request, Event $event)
    {
        $form = $event->regisForm;
        if (!$form) {
            $form = new EventRegisForm;
            $form->event()->associate($event);
        }
        $form->introduction = $request->introduction;
        $form->save();
        return redirect()->route('events.show', ['event' => $event->public_id])
            ->with('status', 'Registration form updated.');
    }
}
