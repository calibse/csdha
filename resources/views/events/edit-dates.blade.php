@php
	$routeParams = ['event' => $event->public_id]
@endphp
<x-layout.user route="events.edit" :$routeParams class="events" title="Edit Dates">
	<x-slot:toolbar>
		<a href="{{ route('events.dates.create', ['event' => $event->public_id]) }}">
			<img class="icon" src="{{ asset('icon/light/plus-circle-duotone.png') }}">

			<span class="text">Add Date</span>
		</a>
	</x-slot:toolbar>
	<article class="article">
		<x-alert/>
		<ul class="item-list">
		@foreach ($dates as $date)
			<li class="item event-date">
				<time class="content">{{ $date->full_date }}</time>
				<span class="context-menu">
					{{--
					<form action="{{ route('events.dates.edit', ['event' => $event->public_id, 'date' => $date->public_id]) }}" class="edit-action">
						<button type="submit"
						@cannot ('update', $date)
							disabled
						@endcannot
						>Edit</button>
					</form>
					--}}
					<form action="{{ route('events.dates.confirmDestroy', ['event' => $event->public_id, 'date' => $date->public_id]) }}" class="delete-action">
						<button type="submit">Delete</button>
					</form>
				</span>
			</li>
		@endforeach
		</ul>
	</article>
</x-layout.user>
