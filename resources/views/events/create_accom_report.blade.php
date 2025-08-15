@php
$routeParams = ['event' => $event->id];
@endphp

<x-layout.user class="editing" editing route="events.show" :$routeParams title="Create Accomplishment Report">
    <h2>{{ $event->title }}</h2>
    <form method="POST" action="{{ route('events.genAccomReport', ['event' => $event->id], false) }}">
	@csrf
	
	<p>
	    <label>Summary of the event</label>
	    <textarea name="summary"></textarea>
	</p>
	<p>
	    <label>Challenges faced</label>
	    <textarea name="challenges"></textarea>
	</p>
	<p>
	    <label>Overall outcome</label>
	    <textarea name="outcome"></textarea>
	</p>
	<p>
	    <button type="submit">Generate PDF</button>
	</p>
    </form>
</x-layout.user>
