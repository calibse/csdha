<x-layout.user has-toolbar index form title="General Plan of Activities" class="form gpoa index">
	<x-slot:toolbar>
	@if ($gpoa)
		@can ('create', 'App\Models\GpoaActivity')
		<a href="{{ route('gpoa.activities.create') }}">
			<img class="icon" src="{{ asset('icon/light/plus.svg') }}">
			<span class="text">Add Activity</span>
		</a>
		@endcan
		@can ('close', 'App\Models\Gpoa')
		<a id="gpoa_close-button" href="{{ route('gpoa.confirmClose') }}">
			<img class="icon" src="{{ asset('icon/light/x-circle.svg') }}">
			<span class="text">Close</span>
		</a>
		@endcan
		@can ('update', 'App\Models\Gpoa')
		<a href="{{ route('gpoa.edit') }}">
			<img class="icon" src="{{ asset('icon/light/pencil-simple-line.svg') }}">
			<span class="text">Edit</span>
		</a>
		@endcan
		@can ('close', 'App\Models\Gpoa')
		<a href="{{ route('gpoa.showGenPdf') }}">
			<img class="icon" src="{{ asset('icon/light/file-plus.svg') }}">
			<span class="text">View Report</span>
		</a>
		@endcan
	@else
		@can ('create', 'App\Models\Gpoa')
		<a href="{{ route('gpoa.create') }}">
			<img class="icon" src="{{ asset('icon/light/plus.svg') }}">
			<span class="text">Create</span>
		</a>
		@endcan
	@endif
		<a href="{{ route('gpoas.old-index') }}">
			<img class="icon" src="{{ asset('icon/light/archive.svg') }}">
			<span class="text">Browse Closed GPOAs</span>
		</a>
	</x-slot:toolbar>
	<div class="article">
	@if (session('status') === 'returned')
		<x-alert>
			Activity returned to the {{ session('position') }}.
		</x-alert>
	@else
		<x-alert item-type="Activity"/>
	@endif
	@if (!$gpoa)
		<p>There is no active GPOA right now.</p>
	@elseif ($gpoa && $activities?->isNotEmpty())
        <table class="main-table articles table-2">
            <colgroup>
                <col style="width: 30%">
                <col style="width: 70%">
            </colgroup>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($activities as $activity)
                <tr class="{{ $loop->last ? 'last-row' : null }}">
                    <td class="activity-name">
						<a href="{{ route('gpoa.activities.show', ['activity' => $activity->public_id]) }}">
                            {{ $activity->name }}
                        </a>
                    </td>
                    <td class="last-row-cell">
			<img class="icon" src="{{ asset("icon/small/light/circle-{$activity->status_color}.svg") }}">
			<span class="text">{{ $activity->full_status }}</span>
			</td>
                </tr>
            @endforeach
            </tbody>
        </table>
		{{ $activities->links('paginator.simple') }}
	@else
		@switch (auth()->user()->position_name)
		@case('president')
		@case('adviser')
		<p>No one has submitted anything yet.</p>
			@break
		@default
			<p>No one has added anything yet.</p>
		@endswitch
	@endif
	</div>
@if ($gpoa)
<x-window class="form" id="gpoa_close" title="Close GPOA">
	<p>
		Are you sure you want to close this GPOA for {{ $gpoa->academicPeriod->term->label }} A.Y. {{ $gpoa->academicPeriod->year_label }}?
	</p>
	<div class="submit-buttons">
		<button id="gpoa_close_close">Cancel</button>
		<form method="post" action="{{ $closeRoute }}">
		@csrf
		@method('PUT')
			<button>Close GPOA</button>
		</form>
	</div>
</x-window>
@endif
</x-layout.user>
