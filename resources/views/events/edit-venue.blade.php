<x-layout.user content-view class="events form" :$backRoute title="Edit event venue">
<div class="article">
        <x-alert errorBag="event-venue_edit" />
	<form method="post" action="{{ $formAction }}">
	@csrf
	@method('PUT')
		<p>
			<label for="venue">Venue</label>
			<input maxlength="255" id="venue" name="venue" value="{{ $errors->any() ? old('venue') : $venue }}">
		</p>
		<p class="form-submit">
			<button>Update</button>
		</p>
	</form>
</div>
</x-layout>
