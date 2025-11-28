<x-layout.user content-view class="events form" :$backRoute title="Edit event banner">
<div class="article">
        <x-alert errorBag="event-banner_edit" />
	<form method="post" action="{{ $formAction }}">
	@csrf
	@method('PUT')
		<p>
			<label for="banner">Banner</label>
			<input id="banner" name="banner"> 
		</p>
		<p class="form-submit">
			<button>Update</button>
		</p>
	</form>
</div>
</x-layout>
