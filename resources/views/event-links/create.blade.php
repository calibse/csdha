<x-layout.user content-view :$backRoute class="events form" title="Add event link">
<div class="article">
	<x-alert errorBag="event-link_create" />
	<form method="post" action="{{ $formAction }}">
	@csrf
		<p>
			<label for="name">Name</label>
			<input required maxlength="255" id="name" name="name" value="{{ old('name') }}">
		</p>
		<p>
			<label for="url">URL</label>
			<input required maxlength="2000" id="url" name="url" value="{{ old('url') }}">
		</p>
		<p class="form-submit">
			<button>Add</button>
		</p>
	</form>
</div>
</x-layout>
