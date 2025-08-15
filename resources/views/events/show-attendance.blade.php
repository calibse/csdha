@php
$routeParams = ['event' => $event->public_id];
@endphp
<x-layout.user title="Event Attendance" route="events.show" :$routeParams class="events" >
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
                @foreach ($date->attendees as $attendee)
                    <tr>
                        <td>{{ $attendee->student->student_id }}</td>
                        <td>{{ $attendee->student->full_name }}</td>
                        <td>{{ $attendee->course_section }}</td>
                        <td>{{ $attendee->entry_time }}</td>
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