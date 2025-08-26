<x-layout.user class="events attachments" :$backRoute title="Attachments">
    <x-slot:toolbar>
        <a href="{{ $createRoute }}">
            <span class="icon"><x-phosphor-plus-circle/></span>
            <span class="text">Create Set</span>
        </a>
    </x-slot:toolbar>
    <article class="article">
        @foreach ($attachmentSets as $set)
            @if ($set->attachments->isNotEmpty())
        <figure class="attachment-set">
            <figcaption class="caption"><a href="{{ route('events.attachments.edit', ['event' => $event->public_id, 'attachment_set' => $set->id]) }}">{{ $set->caption }}</a></figcaption>
            <span class="attachments">
                @foreach ($set->attachments()->orderBy('created_at', 'asc')->get() as $attachment)
                <a href="{{ route('events.attachments.show', ['event' => $event->public_id, 'attachment_set' => $set->id, 'attachment' => $attachment->id]) }}"><img src="{{ route('events.attachments.showPreviewFile', ['event' => $event->public_id, 'attachment_set' => $set->id, 'attachment' => $attachment->id]) }}"></a>
                @endforeach
            </span>
        </figure>
            @else
            <p>
                <a href="{{ route('events.attachments.edit', ['event' => $event->public_id, 'attachment_set' => $set->id]) }}">{{ $set->caption }}</a>
                (Empty)
            </p>
            @endif
        @endforeach
    </article>
</x-layout.user>
