<x-layout.user class="events form" route="events.index" title="Add an event">
	<article class="article">
	    <form method="POST" action="{{ route('events.store', [], false) }}" enctype="multipart/form-data">
			@csrf
			<p>
			    <label>Title</label>
			    <input required name="title">
			</p>
			<p>
				<label>Cover photo</label>
				<input name="cover_photo" type="file" accept="image/png, image/jpeg">
			</p>
			<p>
			    <label>Letter of Intent</label>
			    <input name="letter" type="file" accept="application/pdf">
			</p>
			<p>
				<label>Add Editor(s)</label>
				<select multiple size="5">
			@foreach ($users as $user)
				@unless($user->is(auth()->user()))
					<option value="{{ $user->id }}" name="editors[]">
						{{ $user->fullname }}
					</option>
				@endunless
			@endforeach
				</select>
			</p>
			<p class="form-submit">
			    <button type="submit">Add event</button>
			</p>
	    </form>
	</article>
</x-layout.editing>
