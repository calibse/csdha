<x-layout.user has-toolbar form index title="Accomplishment Reports" class="form gpoa index">
<x-slot:toolbar>
@if ($gpoa)
	<a href="{{ $genRoute }}">
		<img class="icon" src="{{ asset('icon/light/file-plus.svg') }}">
		<span class="text">Gen. Report</span>
	</a>
	{{--
	<a
	@can ('updateAccomReportBG', 'App\Models\Event')
		href="{{ $changeBgRoute }}" id="accom-report-background_edit-button"
	@endcan
	>
		<img class="icon" src="{{ asset('icon/light/pencil-simple-line.svg') }}">
		<span class="text">Change Background</span>
	</a>
	--}}
@endif
</x-slot:toolbar>
<div class="article">
	<x-alert/>
@if ($accomReports->isNotEmpty())
	<table class="main-table articles table-2">
		<colgroup>
			<col style="width: 30%">
			<col style="width: 70%">
		</colgroup>
		<thead>
			<tr>
				<th>Event name</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($accomReports as $accomReport)
			<tr class="{{ $loop->last ? 'last-row' : null }}">
				<td class="activity-name">
					<a href="{{ route('accom-reports.show', ['event' => $accomReport->event->public_id]) }}">
						{{ $accomReport->event->gpoaActivity->name }}
					</a>
				</td>
				<td class="last-row-cell">
					<img class="icon" src="{{ asset("icon/small/light/circle-{$accomReport->status_color}.svg") }}">
					<span class="text">{{ $accomReport->full_status }}</span>
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
	{{ $accomReports->links('paginator.simple') }}
@elseif (!$gpoa)
	<p>There is no active GPOA right now.</p>
@else
	@switch (auth()->user()->position_name)
	@case('president')
		<p>No one has submitted anything yet.</p>
		@break
	@default
		<p>No one has added anything yet.</p>
	@endswitch
@endif
</div>
<x-window class="form" id="accom-report-background_edit" title="Change accom. report background">
	<form method="post" action="{{ $updateBgRoute }}" enctype="multipart/form-data">
	@csrf
	@method('PUT')
		<p>
			<label>Background file</label>
			<input id="background-file" name="background_file" type="file" accept="image/jpeg, image/png, image/webp, image/avif">
		</p>
		<p class="checkbox">
			<input id="remove-background" type="checkbox" name="remove_background" value="1">
			<label for="remove-background">Remove background</label>
		</p>
		<p class="form-submit">
			<button id="accom-report-background_edit_close" type="button">Cancel</button>
			<button>Update</button>
		</p>
	</form>
</x-window>
</x-layout.user>
