@php
$routeParams = ['event' => $event->id];
@endphp
<x-layout.user route="events.show" 
	:$routeParams 
	title="Letter of Intent" 
	class="event"
>
    <figure class="pdf-document">
		<div class="pdf-file">
			<embed src="{{ route('events.letterOfIntent.stream', [
					'event' => $event->id,
					'filename' => basename($event->letter_of_intent)
				], false) }}" 
				type="application/pdf"
			>
		</div>
		<figcaption class="caption">Letter of Intent</figcaption>
    </figure>
</x-layout.user>
