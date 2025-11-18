<x-layout.user content-view class="events form" :$backRoute title="Edit event narrative">
<div class="article">
        <x-alert errorBag="event-narrative_edit" />
	<form method="post" action="{{ $formAction }}">
	@csrf
	@method('PUT')
		<p>
			<label for="narrative">Narrative</label>
			<textarea id="narrative" name="narrative">{{ $errors->any() ? old('narrative') : $narrative }}</textarea>
		</p>
		<p class="form-submit">
			<button>Update</button>
		</p>
	</form>
</div>
</x-layout>
