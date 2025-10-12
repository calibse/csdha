<x-layout.user route="gpoa.index" class="gpoa" title="GPOA Activity">
    <x-slot:toolbar>
    @if ($actions['edit'])
        <a
        @can ('update', $activity)
            href="{{ route('gpoa.activities.edit', ['activity' => $activity->public_id]) }}"
        @endcan
        >
            <img class="icon" src="{{ asset('icon/light/pencil-simple-duotone.png') }}">

            <span class="text">Edit </span>
        </a>
    @endif
    @if ($actions['reject'])
        <a
        @can ('reject', $activity)
            href="{{ route('gpoa.activities.prepareForReject', ['activity' => $activity->public_id]) }}"
        @endcan
        >
            <img class="icon" src="{{ asset('icon/light/x-circle-duotone.png') }}">
            <span class="text">Reject</span>
        </a>
    @endif
    @if ($actions['return'])
        <a
        @can ('return', $activity)
            href="{{ route('gpoa.activities.prepareForReturn', ['activity' => $activity->public_id]) }}"
        @endcan
        >
            <img class="icon" src="{{ asset('icon/light/arrow-fat-line-left-duotone.png') }}">
            <span class="text">Return</span>
        </a>
    @endif
    @if ($actions['submit'])
        <a
        @can ('submit', $activity)
            href="{{ route('gpoa.activities.prepareForSubmit', ['activity' => $activity->public_id]) }}"
        @endcan
        >
            <img class="icon" src="{{ asset('icon/light/arrow-fat-line-right-duotone.png') }}">
            <span class="text">Submit</span>
        </a>
    @endif
    @if ($actions['approve'])
        <a
        @can ('approve', $activity)
            href="{{ route('gpoa.activities.prepareForApprove', ['activity' => $activity->public_id]) }}"
        @endcan
        >
            <img class="icon" src="{{ asset('icon/light/check-circle-duotone.png') }}">
            <span class="text">Approve</span>
        </a>
    @endif
    @if ($actions['delete'])
        <a
        @can ('delete', $activity)
            href="{{ route('gpoa.activities.confirmDestroy', ['activity' => $activity->public_id]) }}"
        @endcan
        >
            <img class="icon" src="{{ asset('icon/light/trash-duotone.png') }}">
            <span class="text">Delete</span>
        </a>
    @endif
    </x-slot>
    <article class="article document">
        <aside>
            <p>Status: {{ $activity->full_status }}</p>
        @if ($activity->comments)
            <pre>"{{ $activity->comments }}"</pre>
        @endif
        @if ($date)
            <p>{{ $date }}</p>
        @endif
        </aside>
        <hr>
        <h2>{{ $activity->name }}</h2>
        <table class="table-2">
            <colgroup>
                <col style="width: 9rem;">
            </colgroup>
            <tr>
                <th>Date</th>
                <td>{{ $activity->date }}</td>
            </tr>
            <tr>
                <th>Objectives</th>
                <td>
                    <pre>{{ $activity->objectives }}</pre>
                </td>
            </tr>
            <tr>
                <th>Participants/Beneficiaries</th>
                <td>{{ $activity->participants }}</td>
            </tr>
            <tr>
                <th>Type of Activity</th>
                <td>{{ $activity->type }}</td>
            </tr>
            <tr>
                <th>Partnership</th>
                <td>{{ $activity->partnership_type }}</td>
            </tr>
            <tr>
                <th>Proposed Budget</th>
                <td>{{ $activity->proposed_budget }}</td>
            </tr>
            <tr>
                <th>Source of Fund</th>
                <td>{{ $activity->fund_source }}</td>
            </tr>
            <tr>
                <th>Mode</th>
                <td>{{ $activity->mode }}</td>
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
        </table>
    </article>
</x-layout.user>

