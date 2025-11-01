<x-layout.user :$backRoute class="events" title="Edit event dates">
	<x-slot:toolbar>
		<a id="event-date_create-button" href="{{ route('events.dates.create', ['event' => $event->public_id]) }}">
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
<x-window class="form" id="event-date_create" title="Add event date">
        <form method="POST" action="{{ $addDateFormAction }}">
        @csrf
            <div class="inline">
                <p>
                    <label>Date</label>
                    <input type="date" name="date" value="{{ old('date') }}">
                </p>
                <p>
                    <label>Start time</label>
                    <input type="time" name="start_time" value="{{ old('start_time') }}">
                </p>
                <p>
                    <label>End time</label>
                    <input type="time" name="end_time" value="{{ old('end_time') }}">
                </p>
            </div>
            <p>
                <button id="event-date_create_close" type="button">Cancel</button>
                <button type="submit">Save</button>
            </p>
        </form>
</x-window>
</x-layout.user>
