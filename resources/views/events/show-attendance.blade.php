@php
$routeParams = ['event' => $event->public_id];
@endphp
<x-layout.user title="Event Attendance" route="events.show" :$routeParams class="events" >
    <x-slot:toolbar>
        <a href="{{ $addRoute }}">
            <span class="icon"><x-phosphor-plus-circle/></span>
            <span class="text">Add Attendee</span>
        </a>
    </x-slot:toolbar>
    <article class="article">
    @foreach ($event->dates as $date)
        @if ($date->attendees->isNotEmpty())
        <h2 class="title">{{ $date->full_date }}</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Course & Year</th>
                        <th>Entry Time</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($date->attendees()->orderBy('pivot_created_at', 'desc')->get() as $attendee)
                    <tr>
                        <td>{{ $attendee->student_id }}</td>
                        <td>{{ $attendee->full_name }}</td>
                        <td>{{ $attendee->course_section }}</td>
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