<x-layout.user :$backRoute class="events event" title="Event">
    <x-slot:toolbar>
        <a 
            @can ('update', $event)
            href="{{ $editRoute }}"
            @endcan
        >
            <span class="icon"><x-phosphor-pencil-simple/></span>
            <span class="text">Edit</span>
        </a>
        <a 
            @can ('viewAccomReport', $event)
            href="{{ $genArRoute }}"
            @endcan
        >
            <span class="icon"><x-phosphor-file-plus/></span>
            <span class="text">Submit AR</span>
        </a>
    </x-slot:toolbar>
    <article class="article document">
        <x-alert/>
        <h2>{{ $activity->name }}</h2>

        <h3>Date</h3>
        @foreach ($event->compactDates() as $date)
        <p>{{ $date }}</p>
        @endforeach

        <h3>Venue</h3>
        <p>{{ $event->venue }}</p>

        <h3>Participants/Beneficiaries</h3>
        <p>{{ $activity->participants }}</p>

        <h3>Type of Activity</h3>
        <p>{{ $activity->type?->name }}</p>

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
    </article>
</x-layout.user>