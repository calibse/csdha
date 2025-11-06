<x-layout.user class="events form" :$backRoute title="Attachment">
<div class="article">
	<figure class="image-file">
		<img src="{{ $fileRoute }}">
	</figure>
	<form method="post" action="{{ $updateRoute }}">
	@method('PUT')
	@csrf
	<fieldset>
		<legend>Layout</legend>
		<p class="checkbox">
			<input id="full-width" name="full_width" type="checkbox" {{ $attachment->full_width ? 'checked' : null }} {{ $attachment->orientation === 'landscape' ? 'disabled' : null }}>
			<label for="full-width">Full width</label>
		</p>
		<p class="checkbox">
			<input id="standalone" name="standalone" type="checkbox" {{ $attachment->standalone ? 'checked' : null }} {{ $attachment->orientation === 'landscape' ? 'disabled' : null }}>
			<label for="standalone">Standalone</label>
		</p>
	</fieldset>
	<p class="form-submit">
		<button 
		@can ('update', $event)
			id="event-attachment_delete-button" 
		@endcan
			form="confirm-delete-form"
		>Delete attachment</button>
		<button
		@cannot ('update', $event)
			disabled
		@endcannot
		>Update</button>
	</p>
	</form>
	<form id="confirm-delete-form" action="{{ $deleteRoute }}"></form>
</div>
<x-window class="form" id="event-attachment_delete" title="Delete attachment">
	<p>
		Are you sure you want to delete this attachment?
	</p>
	<p class="submit-buttons">
		<button id="event-attachment_delete_close">Cancel</button>
		<button form="delete-form">Delete</button>
	</p>
	<form id="delete-form" method="post" action="{{ $deleteFormAction }}">
	@method('DELETE')
	@csrf
	</form>
</x-window>
</x-layout.user>
