<x-layout.user content-view class="events form" :$backRoute title="Edit event description">
<div class="article">
	<x-alert errorBag="event-description_edit" />
	<form method="post" action="{{ $formAction }}">
	@csrf
	@method('PUT')
		<p>
			<label for="description">Description</label>
			<textarea id="description" name="description">{{ $errors->any() ? old('description') : $description }}</textarea>
		</p>
		<p class="form-submit">
			<button>Update</button>
		</p>
	</form>
</div>
</x-layout>
