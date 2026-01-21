<x-layout.user has-toolbar content-view :$backRoute class="events dates" title="Edit event dates">
<x-slot:toolbar>
	<a id="event-date_create-button" href="{{ route('events.dates.create', ['event' => $event->public_id]) }}">
		<img class="icon" src="{{ asset('icon/light/plus.svg') }}">

		<span class="text">Add Date</span>
	</a>
</x-slot:toolbar>
<div class="article">
	<x-alert/>
@if ($dates->isNotEmpty())
	<ul id="event-date-items" class="item-list">
	@foreach ($dates as $date)
@php
$watten = $date->has_attendees;
@endphp
		<li class="item event-date">
			<time id="event-date-{{ ($watten ? 'watten-' : '') . $date->public_id }}" class="content">{{ $date->full_date }}</time>
			<span class="context-menu">
				<form action="{{ route('events.dates.confirmDestroy', ['event' => $event->public_id, 'date' => $date->public_id]) }}" class="delete-action">
					<input id="event-date-{{ ($watten ? 'watten-' : '') . $date->public_id }}_delete-link" type="hidden" value="{{ route('events.dates.destroy', ['event' => $event->public_id, 'date' => $date->public_id]) }}">
					<button id="event-date-{{ ($watten ? 'watten-' : '') . $date->public_id }}_delete-button" type="submit">Delete</button>
				</form>
			</span>
		</li>
	@endforeach
	</ul>
@else
	<p>Nothing here yet.</p>
@endif
</div>
<x-window class="form" id="event-date_create" title="Add event date">
	<form method="POST" action="{{ $addDateFormAction }}">
	@csrf
		<div class="inline">
			<p>
				<label>Date</label>
				<input required pattern="^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$" type="date" name="date" value="{{ old('date') }}">
			</p>
			<p>
				<label>Start time</label>
				<input required pattern="^([01]\d|2[0-3]):([0-5]\d)$" type="time" name="start_time" value="{{ old('start_time') }}">
			</p>
			<p class="last-block">
				<label>End time</label>
				<input required pattern="^([01]\d|2[0-3]):([0-5]\d)$" type="time" name="end_time" value="{{ old('end_time') }}">
			</p>
		</div>
		<p class="button-block">
			<button id="event-date_create_close" type="button">Cancel</button>
			<button type="submit">Save</button>
		</p>
	</form>
</x-window>
<x-window class="form" id="event-date-watten_delete" title="Delete event date">
	<p>
		Attendance records in this date will be destroyed. Are you sure you want to delete this date <strong><time id="event-date-watten_delete-content"></time></strong>?
	</p>
	<div class="submit-buttons">
		<button id="event-date-watten_delete_close">Cancel</button>
		<form method="POST">
		@method('DELETE')
		@csrf
			<button>Delete</button>
		</form>
	</div>
</x-window>
<x-window class="form" id="event-date_delete" title="Delete event date">
	<p>
		Are you sure you want to delete this date <strong><time id="event-date_delete-content"></time></strong>?
	</p>
	<div class="submit-buttons">
		<button id="event-date_delete_close">Cancel</button>
		<form method="POST">
		@method('DELETE')
		@csrf
			<button>Delete</button>
		</form>
	</div>
</x-window>
</x-layout.user>
