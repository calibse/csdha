<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventDeliverable;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EventDeliverableController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:update,event', only: [
                'create',
                'update',
                'edit'
            ]),
            new Middleware('can:update,deliverable', only: [
                'updateTasks'
            ])
        ];
    }

    public function index()
    {
        //
    }

    public function create(Event $event)
    {
        return view('event_deliverables.create', [
            'event' => $event,
            'users' => User::all()
        ]);
    }

    public function store(Request $request, string $event_id)
    {
        $deliverable = new EventDeliverable();
        $deliverable->name = $request->name;
        $deliverable->event()->associate(Event::find($event_id));
        $deliverable->save();

        $deliverable->assignees()->sync($request->assignees);
        $deliverable->save();

        return view('events.show', [
            'event' => Event::find($event_id)
        ]);
    }

    public function show(Request $request, Event $event, 
        EventDeliverable $deliverable)
    {
        return view('event_deliverables.show', [
            'event' => $event,
            'deliverable' => $deliverable
        ]);
    }

    public function edit(Event $event, string $id)
    {
        return view('event_deliverables.edit', [
            'event' => $event,
            'deliverable' => EventDeliverable::find($id),
            'users' => User::all()
        ]);        
    }

    public function update(Request $request, Event $event, string $id)
    {
        $deliverable = EventDeliverable::find($id);
        $deliverable->name = $request->name;
        $deliverable->assignees()->sync($request->assignees);
        $deliverable->save();

        return view('event_deliverables.show', [
            'event' => $event,
            'deliverable' => EventDeliverable::find($id)
        ]);        
    }

    public function updateTasks(Request $request, string $event_id, 
        EventDeliverable $deliverable)
    {
        foreach ($deliverable->tasks as $task) {            
            if ($request->tasks && in_array($task->id, $request->tasks)) {
                $task->is_done = 1;
                $task->save();
                continue;
            }
            $task->is_done = 0;
            $task->save();
        }

        return back()->with('saved', 1);
    }

    public function destroy(string $id)
    {
        //
    }
}
