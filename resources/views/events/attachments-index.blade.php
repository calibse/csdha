<x-layout.user class="events attachments" :$backRoute title="Event Attachments">
<x-slot:toolbar>
	<a 
	@can ('update', $event)
		id="event-attachment-set_create-button" 
		href="{{ $createRoute }}"
	@endcan
	>
		<img class="icon" src="{{ asset('icon/light/plus-circle-duotone.png') }}">
		<span class="text">Create Set</span>
	</a>
</x-slot:toolbar>
<div id="event-attachment-set-items" class="article">
@foreach ($attachmentSets as $set)
	@if ($set->attachments->isNotEmpty())
	<figure class="attachment-set">
		<figcaption class="caption"><a 
		@can ('update', $event)
			id="event-attachment-set-{{ $set->id }}_edit-button" 
			href="{{ route('events.attachments.edit', ['event' => $event->public_id, 'attachment_set' => $set->id]) }}"
		@endcan
		><span id="event-attachment-set-{{ $set->id }}">{{ $set->caption }}</span></a></figcaption>
		<span class="attachments">
		@foreach ($set->attachments()->orderBy('created_at', 'asc')->get() as $attachment)
			<a href="{{ route('events.attachments.show', ['event' => $event->public_id, 'attachment_set' => $set->id, 'attachment' => $attachment->id]) }}">
				<img src="{{ route('events.attachments.showPreviewFile', ['event' => $event->public_id, 'attachment_set' => $set->id, 'attachment' => $attachment->id]) }}">
			</a>
		@endforeach
		</span>
	</figure>
	@else
	<p>
		<a 
		@can ('update', $event)
			id="event-attachment-set-{{ $set->id }}_edit-button" 
			href="{{ route('events.attachments.edit', ['event' => $event->public_id, 'attachment_set' => $set->id]) }}"
		@endcan
		><span id="event-attachment-set-{{ $set->id }}">{{ $set->caption }}</span></a>
(Empty)
	</p>
	@endif
	<input type="hidden" id="event-attachment-set-{{ $set->id }}_update-link" value="{{ route('events.attachments.update', ['event' => $event->public_id, 'attachment_set' => $set->id]) }}">
	<input type="hidden" id="event-attachment-set-{{ $set->id }}_delete-link" value="{{ route('events.attachments.destroySet', ['event' => $event->public_id, 'attachment_set' => $set->id]) }}">
	<span style="display: none;" id="event-attachment-set-{{ $set->id }}_id">{{ $set->id }}</span>
@endforeach
</div>
<x-window class="form" id="event-attachment-set_create" title="Create attachment set">
	<form method="post" action="{{ $createFormAction }}" enctype="multipart/form-data">
	@csrf
		<p>
			<label>Caption</label>
			<input name="caption" value="{{ old('caption') }}"> 
		</p>
		<p>
			<label>Images</label>
			<input id="images-input" name="images[]" type="file" accept="image/jpeg, image/png" multiple>
		</p>
		<p class="form-submit">
			<button id="event-attachment-set_create_close" type="button">Cancel</button>
			<button>Create</button>
		</p>
	</form>
</x-window>
<x-window class="form" id="event-attachment-set_edit" title="Update attachment set">
	<form method="post" enctype="multipart/form-data">
	@method('PUT')
	@csrf
		<p>
			<label for="event-attachment-set-caption_field">Caption</label>
			<input id="event-attachment-set-caption_field" name="caption" value="{{ old('caption') }}"> 
		</p>
		<p>
			<label>Add Images</label>
			<input id="images-input" name="images[]" type="file" accept="image/jpeg, image/png" multiple>
		</p>
		<input type="hidden" id="event-attachment-set_id">
		<p class="form-submit">
			<button type="button" id="event-attachment-set_edit_close">Cancel</button>
			<button id="event-attachment-set_delete-button" form="delete-form">Delete Set</button>
			<button>Update</button>
		</p>
	</form>
	<form id="delete-form"></form>
</x-window>
<x-window class="form" id="event-attachment-set_delete" title="Delete attachment set">
        <p>
            Are you sure you want to delete this attachment set
            "<strong id="event-attachment-set_delete-content"></strong>"?
        </p>
        <div class="submit-buttons">
                <button id="event-attachment-set_delete_close">Cancel</button>
                <form method="POST">
                @method('DELETE')
                @csrf
                        <button>Delete</button>
                </form>
        </div>
</x-window>
</x-layout.user>
