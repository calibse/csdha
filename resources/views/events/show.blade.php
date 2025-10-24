<x-layout.user :$backRoute class="events event" title="Event">
    <x-slot:toolbar>
        <a
            @can ('update', $event)
            href="{{ $editRoute }}"
            @endcan
        >
            <img class="icon" src="{{ asset('icon/light/pencil-simple-duotone.png') }}">

            <span class="text">Edit</span>
        </a>
        <a
            @can ('viewAccomReport', $event)
            href="{{ $genArRoute }}"
            @endcan
        >
            <img class="icon" src="{{ asset('icon/light/file-plus-duotone.png') }}">
            <span class="text">{{ $event->accomReport?->approved_at ? 'View' : 'Submit' }} AR</span>
        </a>
    </x-slot:toolbar>
    <div class="article document">
        <x-alert/>

        <h2>{{ $activity->name }}</h2>
        <table class="table-2">
            <colgroup>
                <col style="width: 12em;">
            </colgroup>
            <tr>
                <th>Date</th>
                <td>
                    <ul>
                    @foreach ($event->compactDates() as $date)
                        <li>{{ $date }}</li>
                    @endforeach
                    </ul>
                </td>
            </tr>
            @can ('register', $event)
            <tr>
                <th>Registration Form</th>
                <td>
                    <a href="{{ $regisRoute }}">
                        {{ $regisRoute }}
                    </a>
                </td>
            </tr>
            @endcan
            <tr>
                <th>Venue</th>
                <td>{{ $event->venue }}</td>
            </tr>
            <tr>
                <th>Participants / Beneficiaries</th>
                <td>{{ $activity->participants }}</td>
            </tr>
            <tr>
                <th>Type of Activity</th>
                <td>{{ $activity->type }}</td>
            </tr>
            <tr>
                <th>Objectives</th>
                <td><pre>{{ $activity->objectives }}</pre></td>
            </tr>
            <tr>
                <th>Description</th>
                <td><pre>{{ $event->description }}</pre></td>
            </tr>
            <tr>
                <th>Narrative</th>
                <td><pre>{{ $event->narrative }}</pre></td>
            </tr>
            <tr>
                <th>Event Head</th>
                <td>
                    <ul>
                        @foreach ($eventHeads as $eventHead)
                        <li>{{ $eventHead->full_name }}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            @if ($coheads->isNotEmpty())
            <tr>
                <th>Co-head</th>
                <td>
                    <ul>
                        @foreach ($coheads as $cohead)
                        <li>{{ $cohead->full_name }}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            @endif
            @can ('recordAttendance', $event)
            <tr>
                <th>Attendance</th>
                <td><a href="{{ $attendanceRoute }}">Attendance</a></td>
            </tr>
            @endcan
        </table>


        {{--
        <h3>Date</h3>
        @foreach ($event->compactDates() as $date)
        <p>{{ $date }}</p>
        @endforeach

        <h3>Venue</h3>
        <p>{{ $event->venue }}</p>

        <h3>Participants/Beneficiaries</h3>
        <p>{{ $activity->participants }}</p>

        <h3>Type of Activity</h3>
        <p>{{ $activity->type }}</p>

        <h3>Objectives</h3>
        <pre>{{ $activity->objectives }}</pre>

        <h3>Description</h3>
        <pre>{{ $event->description }}</pre>

        <h3>Narrative</h3>
        <pre>{{ $event->narrative }}</pre>

        <h3>Event Head</h3>
        <p>Event Head</p>
        <ul>
            @foreach ($eventHeads as $eventHead)
            <li>{{ $eventHead->full_name }}</li>
            @endforeach
        </ul>
        @if ($coheads->isNotEmpty())
        <p>Co-head</p>
        <ul>
                @foreach ($coheads as $cohead)
            <li>{{ $cohead->full_name }}</li>
                @endforeach
        </ul>
        @endif

        @can ('register', $event)
        <h3>Registration Form</h3>
        <p>
            <a href="{{ $regisRoute }}">
                {{ $regisRoute }}
            </a>
        </p>
        @endcan

        @can ('recordAttendance', $event)
        <h3>Attendance</h3>
        <p><a href="{{ $attendanceRoute }}">Attendance</a></p>
        @endcan

        @can ('evaluate', $event)
        <h3>Evaluation Form</h3>
        <p>
            <a href="{{ $evalRoute }}">
                {{ $evalRoute }}
            </a>
        </p>
        @endcan
        --}}
    </div>
</x-layout.user>
