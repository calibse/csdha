<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Event;
use App\Models\EventAttachment;
use App\Models\EventAttachmentSet;
use App\Services\Image;
use App\Http\Requests\SaveAttachmentSetRequest;

class EventAttachmentController extends Controller
{
    public function index(Event $event)
    {
        $attachmentSets = $event->attachmentSets()->orderBy('created_at', 'asc')
            ->get();
        return view('events.attachments-index', [
            'event' => $event,
            'attachmentSets' => $attachmentSets,
            'backRoute' => route('events.show', [
                'event' => $event
            ]),
            'createRoute' => route('events.attachments.create', [
                'event' => $event
            ])
        ]);
    }

    public function create(Event $event)
    {
        return view('events.attachments-create', [
            'set' => null,
            'backRoute' => route('events.attachments.index', [
                'event' => $event
            ]),
            'formAction' => route('events.attachments.store', [
                'event' => $event
            ]),
        ]);
    }

    public function store(SaveAttachmentSetRequest $request, Event $event)
    {
        self::storeOrUpdate($request, $event);
        return redirect()->route('events.attachments.index', [
            'event' => $event
        ]);
    }


    public function showPreviewFile(Event $event, EventAttachmentSet 
            $attachmentSet, EventAttachment $attachment)
    {
        return response()->file(Storage::path($attachment->preview_filepath));
    }

    public function showFullFile(Event $event, EventAttachmentSet 
            $attachmentSet, EventAttachment $attachment)
    {
        return response()->file(Storage::path($attachment->image_filepath));
    }

    public function show(Event $event, EventAttachmentSet $attachmentSet, 
            EventAttachment $attachment)
    {
        return view('events.attachments-show', [
            'attachment' => $attachment,
            'backRoute' => route('events.attachments.index', [
                'event' => $event
            ]),
            'fileRoute' => route('events.attachments.showFullFile', [
                'event' => $event->public_id, 
                'attachment_set' => $attachmentSet->id, 
                'attachment' => $attachment->id
            ]),
            'deleteRoute' => route('events.attachments.confirmDestroy', [
                'event' => $event,
                'attachment_set' => $attachmentSet->id,
                'attachment' => $attachment->id
            ]),
            'updateRoute' => route('events.attachments.updateAttachment', [
                'event' => $event,
                'attachment_set' => $attachmentSet->id,
                'attachment' => $attachment->id
            ])
        ]);
    }

    public function edit(Event $event, EventAttachmentSet $attachmentSet)
    {
        return view('events.attachments-create', [
            'set' => $attachmentSet,
            'backRoute' => route('events.attachments.index', [
                'event' => $event
            ]),
            'formAction' => route('events.attachments.update', [
                'event' => $event,
                'attachment_set' => $attachmentSet->id
            ]),
            'deleteRoute' => route('events.attachments.confirmDestroySet', [
                'event' => $event,
                'attachment_set' => $attachmentSet->id
            ])
        ]);
    }

    public function update(SaveAttachmentSetRequest $request, Event $event, 
            EventAttachmentSet $attachmentSet)
    {
        self::storeOrUpdate($request, $event, $attachmentSet);
        return redirect()->route('events.attachments.index', [
            'event' => $event
        ]);
    }

    public function updateAttachment(Request $request, Event $event, 
            EventAttachmentSet $attachmentSet, EventAttachment $attachment)
    {
        $attachment->standalone = $request->boolean('standalone', false);
        $attachment->full_width = $request->boolean('full_width', false);
        $attachment->save();
        return redirect()->route('events.attachments.index', [
            'event' => $event
        ]);
    }

    public function confirmDestroySet(Event $event, EventAttachmentSet 
            $attachmentSet)
    {
        return view('events.attachment-sets-delete', [
            'set' => $attachmentSet,
            'backRoute' => route('events.attachments.edit', [
                'event' => $event,
                'attachment_set' => $attachmentSet->id
            ]),
            'formAction' => route('events.attachments.destroySet', [
                'event' => $event,
                'attachment_set' => $attachmentSet->id
            ]),
        ]);
    }

    public function destroySet(Event $event, EventAttachmentSet $attachmentSet)
    {
        $attachmentSet->attachments()->delete();
        $attachmentSet->delete();
        return redirect()->route('events.attachments.index', [
            'event' => $event
        ]);
    }

    public function confirmDestroy(Event $event, EventAttachmentSet $attachmentSet, 
            EventAttachment $attachment)
    {
        return view('events.attachments-delete', [
            'attachment' => $attachment,
            'backRoute' => route('events.attachments.show', [
                'event' => $event,
                'attachment_set' => $attachmentSet->id,
                'attachment' => $attachment
            ]),
            'formAction' => route('events.attachments.destroy', [
                'event' => $event,
                'attachment_set' => $attachmentSet->id,
                'attachment' => $attachment
            ]),
        ]);
    }

    public function destroy(Event $event, EventAttachmentSet $attachmentSet, 
            EventAttachment $attachment)
    {
        $attachment->delete();
        return redirect()->route('events.attachments.index', [
            'event' => $event
        ]);
    }

    private static function storeOrUpdate(Request $request, Event $event,
            EventAttachmentSet $attachmentSet = new EventAttachmentSet())
    {
        $attachmentSet->event()->associate($event);
        $attachmentSet->caption = $request->caption;
        $attachmentSet->event()->associate($event);
        $attachmentSet->save();
        $images = $request->file('images', []);
        foreach ($images as $image) {
            $newImage = new Image($image->get());
            $imageFilepath = "events/event_{$event->id}/attachment_"
                . Str::random(8) . '.jpg';
            $previewFilepath = "events/event_{$event->id}/attachment_preview_"
                . Str::random(8) . '.jpg';
            Storage::put($imageFilepath, (string) $newImage->scaleDown(800));
            Storage::put($previewFilepath, (string) $newImage->scaleDown(80));
            $attachment = new EventAttachment();
            $attachment->image_filepath = $imageFilepath;
            $attachment->preview_filepath = $previewFilepath;
            $attachment->orientation = $newImage->orientation();
            $attachment->standalone = false;
            $attachment->full_width = false;
            $attachment->set()->associate($attachmentSet);
            $attachment->save();
        }
    }
}
