<x-layout.user class="events attachments form create" :$backRoute title="{{ $set ? 'Update' : 'Create' }} Attachment Set">
<div class="article">
	<x-alert/>
	<form method="post" action="{{ $formAction }}" enctype="multipart/form-data">
	@if ($set)
		@method('PUT')
	@endif
	@csrf
		<p>
			<label>Caption</label>
			<input name="caption" value="{{ old('caption') ?? ($set ? $set->caption : null) }}">
		</p>
		<p>
			<label>{{ $set ? 'Add' : null }} Images</label>
			<input id="images-input" name="images[]" type="file" accept="image/jpeg, image/png" multiple>
		</p>
		<p class="form-submit">
		@if ($set)
			<button form="delete-form">Delete Set</button>
		@endif
			<button>{{ $set ? 'Update' : 'Create' }}</button>
		</p>
	</form>
@if ($set)
	<form id="delete-form" action="{{ $deleteRoute }}"></form>
@endif
</div>
</x-layout.user>
