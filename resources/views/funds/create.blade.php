<x-layout.user route="funds.index" title="Add Fund Allocation">
    <form method="POST" action="{{ route('funds.store', [], false) }}" enctype="multipart/form-data">
	@csrf

	<p>
	    <label>Event</label>
	    <select required name="event_id">
		<option value="">-- Select Event --</option>
		@foreach ($events as $event)
		    @unless ($event->fund)
			
			<option value="{{ $event->id }}">{{ $event->title }}</option>
		    @endunless
		@endforeach
		
	    </select>
	</p>
	<p>
	    <label>Collected</label>
	    <input required name="collected" type="number" step="0.1">
	</p>
	<p>
	    <label>Spent</label>
	    <input required name="spent" type="number" step="0.1">
	</p>
	<p>
	    <button type="submit">Save Fund Allocation</button>
	</p>
    </form>
</x-layout.editing>
