<x-layout.user index title="General Plan of Activities" class="gpoa index">
	<x-slot:toolbar>
	@if ($gpoa)
		@can ('create', 'App\Models\GpoaActivity')
		<a href="{{ route('gpoa.activities.create') }}">
			<span class="icon"><x-phosphor-plus-circle/></span>
			<span class="text">Add Activity</span>
		</a>
		@endcan
		@can ('close', 'App\Models\Gpoa')
		<a href="{{ route('gpoa.showGenPdf') }}">
				<span class="icon"><x-phosphor-file-plus/></span>
				<span class="text">Gen. PDF</span>
		</a>
		@endcan
		@can ('update', 'App\Models\Gpoa')
		<a href="{{ route('gpoa.edit') }}">
				<span class="icon"><x-phosphor-pencil-simple/></span>
				<span class="text">Edit</span>
		</a>
		@endcan
		@can ('close', 'App\Models\Gpoa')
		<a href="{{ route('gpoa.confirmClose') }}">
				<span class="icon"><x-phosphor-archive/></span>
				<span class="text">Close</span>
		</a>
		@endcan
	@else
		@can ('create', 'App\Models\Gpoa')
		<a href="{{ route('gpoa.create') }}">
			<span class="icon"><x-phosphor-plus-circle/></span>
			<span class="text">Create</span>
		</a>
		@endcan
	@endif
	</x-slot:toolbar>
	<article class="article">
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
        <table class="table-2">
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
                <tr>
                    <td class="activity-name">
						<a href="{{ route('gpoa.activities.show', ['activity' => $activity->public_id]) }}">
                            {{ $activity->name }}
                        </a>
                    </td>
                    <td>{{ $activity->current_status }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{--
		<ul class="item-list-icon">
		@foreach ($activities as $activity)
			<li class="item">
				<div class="icon">
					<x-phosphor-blueprint/>
				</div>
				<div class="content">
					<p class="title">
						<a href="{{ route('gpoa.activities.show', ['activity' => $activity->public_id]) }}">
							{{ mb_strimwidth($activity->name, 0, 70, '...') }}
						</a>
					</p>
					<p class="subtitle">Status: {{ mb_strimwidth($activity->current_status, 0, 70, '...') }} </p>
				</div>
			</li>
		@endforeach
		</ul>
        --}}
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
	</article>
</x-layout.user>
