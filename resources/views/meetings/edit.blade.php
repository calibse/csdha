@php
$routeParams = ['meeting' => $meeting->id];
@endphp
<x-layout.user class="editing" editing route="meetings.show" :$routeParams title="Edit meeting">
    <form method="POST" action="{{ route('meetings.update', ['meeting' => $meeting->id], false) }}" enctype="multipart/form-data">
	@method("PUT")
	@csrf
	
	<p>
	    <label>Title</label>
	    <input required name="title" type="text" value="{{ $meeting->title }}">
	</p>
	<p>
	    <label>Agenda</label>
	    <textarea required name="agenda">{{ $meeting->agenda }}</textarea>
	</p>
	<p>
	    <label>Date</label>
	    <input required name="date" type="date" value="{{ $meeting->date }}">
	</p>
	<p>
	    <label>Venue</label>
	    <input required name="venue" type="text" value="{{ $meeting->venue }}">
	</p>
	<p>
	    <label>Participants</label>
	    <input required name="participants" type="number" value="{{ $meeting->participants }}">
	</p>
	<p>
	    <label>Minutes File</label>
	    <input name="minutes" type="file" accept="application/pdf">
	</p>
	<p>
	    <button type="submit">Update meeting</button>
	</p>
    </form>
</x-layout.editing>
