<x-layout.user has-toolbar title="Event Attendance" :$backRoute class="events event attendance" >
<x-slot:toolbar>
	<a
	@can ('addAttendee', $event)
		href="{{ $addRoute }}"
	@endcan
	>
		<img class="icon" src="{{ asset('icon/light/plus.svg') }}">
		<span class="text">Add Attendee</span>
	</a>
</x-slot:toolbar>
<div class="article">
	<x-alert/>
@if ($eventDates->isNotEmpty())
	@foreach ($eventDates as $date)
	<h2 class="title">{{ $date->full_date }}</h2>
		@if ($date->attendees->isNotEmpty())
	<table class="main-table table-2">
		<thead>
			<tr>
				<th>Student ID</th>
				<th>Name</th>
				<th>Course & Year</th>
				<th>Entry Time</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($date->attendees()->orderByPivot('created_at', 'desc')->get() as $attendee)
			<tr>
				<td>{{ $attendee->student_id }}</td>
				<td>{{ $attendee->full_name }}</td>
				<td>{{ $attendee->course_section }}</td>
				<td>{{ $attendee->entry_time->setTimezone(config('timezone'))->format('h:i A') }}</td>
			</tr>
		@endforeach
		</tbody>
	</table>
		@else
	<p>No attendees yet.</p>
		@endif
	@endforeach
@else
	<p>There is no event date.</p>
@endif
</div>
</x-layout.user>
