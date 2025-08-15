<x-layout.user class="editing" editing route="meetings.index" title="New meeting">
    <form method="POST" action="{{ route('meetings.store', [], false) }}" enctype="multipart/form-data">
	@csrf
	
	<p>
	    <label>Title</label>
	    <input required name="title" type="text">
	</p>
	<p>
	    <label>Agenda</label>
	    <textarea required name="agenda"></textarea>
	</p>
	<p>
	    <label>Date</label>
	    <input required name="date" type="date">
	</p>
	<p>
	    <label>Venue</label>
	    <input required name="venue" type="text">
	</p>
	<p>
	    <label>Participants</label>
	    <input required name="participants" type="number">
	</p>
	<p>
	    <label>Minutes File</label>
	    <input required name="minutes" type="file" accept="application/pdf">
	</p>
	<p>
	    <button type="submit">Save meeting</button>
	</p>
    </form>
</x-layout.editing>
