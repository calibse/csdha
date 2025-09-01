<x-layout.user title="Event Attendance" :$backRoute class="events" >
    <x-slot:toolbar>
        <a 
            @can ('addAttendee', $event)
            href="{{ $addRoute }}"
            @endcan
        >
            <span class="icon"><x-phosphor-plus-circle/></span>
            <span class="text">Add Attendee</span>
        </a>
    </x-slot:toolbar>
    <article class="article">
        <x-alert/>
        @foreach ($eventDates as $date)
        <h2 class="title">{{ $date->full_date }}</h2>
            @if ($date->officerAttendees->isNotEmpty())
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Entry Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($date->officerAttendees()->orderBy('pivot_created_at', 'desc')->get() as $attendee)
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