<x-layout.user has-toolbar title="Event Attendance" :$backRoute class="events" >
    <x-slot:toolbar>
        <a
            @can ('addAttendee', $event)
            href="{{ $addRoute }}"
            @endcan
        >
		<img class="icon" src="{{ asset('icon/light/plus-circle.png') }}">
            <span class="text">Add Attendee</span>
        </a>
    </x-slot:toolbar>
    <article class="article">
        <x-alert/>
        @foreach ($eventDates as $date)
        <h2 class="title">{{ $date->full_date }}</h2>
            @if ($date->officerAttendees->isNotEmpty())
        <div class="">
            <table class="table-3">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Entry Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($date->officerAttendees()->orderByPivot('created_at', 'desc')->get() as $attendee)
                    <tr>
                        <td>{{ $attendee->full_name }}</td>
                        <td>{{ $attendee->position->name }}</td>
                        <td>{{ $attendee->entry_time->setTimezone(config('timezone'))->format('h:i A') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
            @else
        <p>No attendees yet.</p>
            @endif
        @endforeach
    </article>
</x-layout.user>
