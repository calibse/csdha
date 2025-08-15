@php
$routeParams = ['meeting' => $meeting->id];
@endphp

<x-layout.user editing route="meetings.show" :$routeParams title="Minutes File" class="meetings editing view">
    <figure>
	<embed src="{{ route('meetings.showMinutesFile', ['meeting' => $meeting->id, 'filename' => basename($meeting->minutes_file)], false) }}" type="application/pdf">
	<figcaption>Minutes File</figcaption>
    </figure>
</x-layout.user>
