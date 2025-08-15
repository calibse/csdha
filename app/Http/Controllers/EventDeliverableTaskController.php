<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventDeliverable;
use App\Models\EventDeliverableTask;
use App\Models\Event;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EventDeliverableTaskController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:update,event', only: [
                'index',
                'store',
                'update',
                'destroy',
            ]),
        ];
    }

    public function index(Event $event, string $deliverable_id)
    {
        $deliverable = EventDeliverable::find($deliverable_id);

        return view('event_deliverable_tasks/index', [
            'event' => $event,
            'deliverable' => $deliverable,
            'tasks' => $deliverable->tasks
        ]);
    }

    public function create()
    {
    }

    public function store(Request $request, Event $event, 
        string $deliverable_id)
    {
        $deliverable = EventDeliverable::find($deliverable_id);
        $task = new EventDeliverableTask;
        $task->name = $request->name;
        $task->is_done = 0;
        $task->eventDeliverable()->associate($deliverable);
        $task->save();

        return redirect()->route('events.deliverables.show', [
            'event' => $event,
            'deliverable' => EventDeliverable::find($deliverable_id)
        ]);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $event_id, string $deliverable_id, 
        string $id)
    {
    }

    public function update(Request $request, Event $event, 
        string $deliverable_id, string $id)
    {
        $task = EventDeliverableTask::find($id);
        $task->name = $request->name;
        $task->save();

        return back()->with('saved', 1);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, string $deliverable_id, string $id)
    {
        $task = EventDeliverableTask::find($id);
        $task->delete();
        
        return back()->with('saved', 1);
    }
}
