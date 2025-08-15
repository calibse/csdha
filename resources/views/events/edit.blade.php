<x-layout.user class="events form" :$backRoute title="Edit event">
	<article class="article">
		<x-alert/>
	    <form method="post" action="{{ $formAction }}" enctype="multipart/form-data">
			@method("PUT")
			@csrf
			<p>
				<label>Dates</label>
				<a href="{{ $dateRoute }}">Edit here</a>
			</p>
			<p>
				<label>Registration form</label>
				<a href="{{ $regisRoute }}">Edit here</a>
			</p>
			<p>
				<label>Evaluation form</label>
				<a href="{{ $evalRoute }}">Edit here</a>
			</p>
			<p>
				<label>Tag <small>(for QR code label)</small></label>
				<input name="tag" value="{{ $event->tag }}">
			</p>
			<p>
				<label>Venue</label>
				<input name="venue" value="{{ $event->venue }}">
			</p>
			<p>
				<label>Description</label>
				<textarea name="description">{{ $event->description }}</textarea>
			</p>
			<p>
				<label>Narrative</label>
				<textarea name="narrative">{{ $event->narrative }}</textarea>
			</p>
			<p class="form-submit">
			    <button type="submit">Update</button>
			</p>
	    </form>
	</article>
</x-layout>
