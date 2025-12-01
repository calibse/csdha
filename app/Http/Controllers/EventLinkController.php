<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventLink;
use App\Models\Event;
use App\Http\Requests\StoreEventLinkRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EventLinkController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth.event:update,event', only: [
                'index', 'create', 'store', 'confirmDestroy', 'destroy'
            ]),
        ];
    }

    public function index(Event $event)
    {
        return view('event-links.index', [
            'event' => $event,
            'links' => $event->links,
            'createRoute' => route('events.links.create', [
                'event' => $event->public_id
            ]),
            'backRoute' => route('events.show', [
                'event' => $event->public_id
            ]),
            'addFormAction' => route('events.links.store', [
                'event' => $event->public_id
            ]),
        ]);
    }

    public function create(Event $event)
    {
        return view('event-links.create', [
            'backRoute' => route('events.links.index', [
                'event' => $event->public_id
            ]),
            'formAction' => route('events.links.store', [
                'event' => $event->public_id
            ]),
        ]);
    }

    public function store(StoreEventLinkRequest $request, Event $event)
    {
        $link = new EventLink;
        $link->name = $request->name;
        $link->url = $request->url;
        $link->event()->associate($event);
        $link->save();
        return redirect()->route('events.links.index', [
            'event' => $event->public_id
        ])->with('status', 'Event link added.');
    }

    public function confirmDestroy(Event $event, EventLink $link)
    {
        return view('event-links.delete', [
            'link' => $link,
            'backRoute' => route('events.links.index', [
                'event' => $event->public_id
            ]),
            'formAction' => route('events.links.destroy', [
                'event' => $event->public_id,
                'link' => $link->id
            ]),
        ]);
    }

    public function destroy(Event $event, EventLink $link)
    {
        $link->delete();
        return redirect()->route('events.links.index', [
            'event' => $event->public_id
        ])->with('status', 'Event link deleted.');
    }
}
