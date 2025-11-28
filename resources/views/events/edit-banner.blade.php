<x-layout.user content-view class="events form" :$backRoute title="Edit event banner">
<div class="article">
        <x-alert errorBag="event-banner_edit" />
	<form method="post" action="{{ $formAction }}" enctype="multipart/form-data">
	@csrf
	@method('PUT')
		<p>
			<label for="banner">Banner</label>
			<input id="banner" name="banner" type="file" accept="image/jpeg, image/png, image/webp, image/avif">
		</p>
		<p class="checkbox">
			<input id="remove-banner" type="checkbox" name="remove_banner" value="1">
			<label for="remove-banner">Remove banner</label>
		</p>
		<p class="form-submit">
			<button>Update</button>
		</p>
	</form>
</div>
</x-layout>
