<x-layout.user route="gpoa.index" class="gpoa" title="GPOA Activity">
    <x-slot:toolbar>
    @if ($actions['edit'])
        <a 
        @can ('update', $activity)
            href="{{ route('gpoa.activities.edit', ['activity' => $activity->public_id]) }}"
        @endcan
        >
            <span class="icon"><x-phosphor-pencil-simple/></span> 
            <span class="text">Edit </span>
        </a>
    @endif
    @if ($actions['return'])
        <a 
        @can ('return', $activity)
            href="{{ route('gpoa.activities.prepareForReturn', ['activity' => $activity->public_id]) }}"
        @endcan
        >
            <span class="icon"><x-phosphor-arrow-fat-line-left/></span> 
            <span class="text">Return</span>
        </a>
    @endif
    @if ($actions['reject'])
        <a 
        @can ('reject', $activity)
            href="{{ route('gpoa.activities.prepareForReject', ['activity' => $activity->public_id]) }}"
        @endcan
        >
            <span class="icon"><x-phosphor-x-circle/></span> 
            <span class="text">Reject</span>
        </a>
    @endif
    @if ($actions['submit'])
        <a 
        @can ('submit', $activity)
            href="{{ route('gpoa.activities.prepareForSubmit', ['activity' => $activity->public_id]) }}"
        @endcan
        >
            <span class="icon"><x-phosphor-arrow-fat-line-right/></span> 
            <span class="text">Submit</span>
        </a>
    @endif
    @if ($actions['approve'])
        <a 
        @can ('approve', $activity)
            href="{{ route('gpoa.activities.prepareForApprove', ['activity' => $activity->public_id]) }}"
        @endcan
        >
            <span class="icon"><x-phosphor-check-circle/></span> 
            <span class="text">Approve</span>
        </a>
    @endif
    @if ($actions['delete'])
        <a 
        @can ('delete', $activity)
            href="{{ route('gpoa.activities.confirmDestroy', ['activity' => $activity->public_id]) }}"
        @endcan
        >
            <span class="icon"><x-phosphor-trash/></span> 
            <span class="text">Delete</span>
        </a>
    @endif
    </x-slot>
    <article class="article document">
        <aside>
        @if ($activity->comments)
            <header>
                <p><strong>{{ $activity->comments_purpose }}</strong></p>
            </header>
            <pre>{{ $activity->comments }}</pre>
        @endif
            <p>Status: {{ $activity->current_status }}</p>
            @if ($date)
            <p>Date: {{ $date }}</p>
            @endif
        </aside>
        <hr>
        <h2>{{ $activity->name }}</h2>

        <h3>Date</h3>
        <p>{{ $activity->date }}</p>

        <h3>Objectives</h3>
        <pre>{{ $activity->objectives }}</pre>

        <h3>Participants/Beneficiaries</h3>
        <p>{{ $activity->participants }}</p>

        <h3>Type of Activity</h3>
        <p>{{ $activity->type?->name }}</p>

        <h3>Partnership</h3>
        <p>{{ $activity->partnershipType?->name }}</p>

        <h3>Proposed Budget</h3>
        <p>{{ $activity->proposed_budget }}</p>

        <h3>Source of Fund</h3>
        <p>{{ $activity->fundSource?->name }}</p>

        <h3>Mode</h3>
        <p>{{ $activity->mode?->name }}</p>

        <h3>Event Head</h3>
        <p>Event Head</p>
        <ul>
        @foreach ($activity->eventHeads()->wherePivot('role', 'event head')->get() as $eventHead)
            <li>{{ $eventHead->full_name }}</li>
        @endforeach
        </ul>
        @php
$coheads = $activity->eventHeads()->wherePivot('role', 'co-head')->get();
        @endphp
    @if ($coheads->isNotEmpty())
        <p>Co-head</p>
        <ul>
        @foreach ($coheads as $cohead)
            <li>{{ $cohead->full_name }}</li>
        @endforeach
        </ul>
    @endif
    </article>
</x-layout.user>

