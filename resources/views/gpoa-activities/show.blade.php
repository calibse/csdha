<x-layout.user has-toolbar form route="gpoa.index" class="gpoa activity form" title="GPOA Activity">
<x-slot:toolbar>
@if ($actions['edit'])
	<a
	@can ('update', $activity)
		href="{{ route('gpoa.activities.edit', ['activity' => $activity->public_id]) }}"
	@endcan
	>
		<img class="icon" src="{{ asset('icon/light/pencil-simple-line.svg') }}">
		<span class="text">Edit </span>
	</a>
@endif
@if ($actions['reject'])
	<a 
	@can ('reject', $activity)
		href="{{ route('gpoa.activities.prepareForReject', ['activity' => $activity->public_id]) }}" id="gpoa-activity_reject-button" data-action="{{ $rejectActionRoute }}"
	@endcan
	>
		<img class="icon" src="{{ asset('icon/light/x-circle.svg') }}">
		<span class="text">Reject</span>
	</a>
@endif
@if ($actions['return'])
	<a 
	@can ('return', $activity)
		href="{{ route('gpoa.activities.prepareForReturn', ['activity' => $activity->public_id]) }}" id="gpoa-activity_return-button" data-action="{{ $returnActionRoute }}"
	@endcan
	>
		<img class="icon" src="{{ asset('icon/light/arrow-u-down-left.svg') }}">
		<span class="text">Return</span>
	</a>
@endif
@if ($actions['submit'])
	<a 
	@can ('submit', $activity)
		href="{{ route('gpoa.activities.prepareForSubmit', ['activity' => $activity->public_id]) }}" id="gpoa-activity_submit-button" data-action="{{ $submitActionRoute }}"
	@endcan
	>
		<img class="icon" src="{{ asset('icon/light/arrow-bend-down-right.svg') }}">
		<span class="text">Submit</span>
	</a>
@endif
@if ($actions['approve'])
	<a 
	@can ('approve', $activity)
		href="{{ route('gpoa.activities.prepareForApprove', ['activity' => $activity->public_id]) }}" id="gpoa-activity_approve-button" data-action="{{ $approveActionRoute }}"
	@endcan
	>
		<img class="icon" src="{{ asset('icon/light/check-circle.svg') }}">
		<span class="text">Approve</span>
	</a>
@endif
@if ($actions['delete'])
	<a 
	@can ('delete', $activity)
		href="{{ route('gpoa.activities.confirmDestroy', ['activity' => $activity->public_id]) }}" id="gpoa-activity_delete-button"
	@endcan
	>
		<img class="icon" src="{{ asset('icon/light/trash.svg') }}">
		<span class="text">Delete</span>
	</a>
@endif
</x-slot>
<div class="article has-item-full-content document">
	<x-alert/>
	<aside class="item-full-content main-status">
		<div class="content-block">
			<p class="title">Status</p>
			<p>
				<img class="icon" src="{{ asset("icon/small/light/circle-{$activity->status_color}.svg") }}">

				<span class="text">
					{{ $activity->full_status }}
				</span>
			</p>
		@if ($activity->comments)
			<div>
				<img class="icon" src="{{ asset('icon/small/light/chat-text.svg') }}">
				<pre class="text">"{{ $activity->comments }}"</pre>
			</div>
		@endif
		@if ($date)
			<p>
				<img class="icon" src="{{ asset('icon/small/light/calendar-dot.svg') }}">
				<span class="text">
					{{ $date }}
				</span>
			</p>
		@endif
		<hr>
		</div>
	</aside>
	<div class="item-full-content">
		<div class="content-block">
			<h2>{{ $activity->name }}</h2>
			<table class="table-2">
				<colgroup>
					<col style="width: 12em;">
				</colgroup>
				<tbody>
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
						<th>Participants / Beneficiaries</th>
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
		</div>
	</div>
</div>
<x-window class="form" id="gpoa-activity_prepare" title="{{ $action ?? '[Action]' }} activity">
        <form method="post" action="{{ $formActionUrl }}">
        @method('PUT')
        @csrf
                <p>
                        <label>Comments</label>
                        <textarea name="comments"></textarea>
                </p>
                <p class="form-submit">
                        <button type="button" id="gpoa-activity_prepare_close">Cancel</button>
                        <button id="gpoa-activity_prepare-button">{{ $action ?? '[Action]' }}</button>
                </p>
        </form>
</x-window>
<x-window class="form" id="gpoa-activity_delete" title="Delete GPOA activity">
	<p>
		Are you sure you want to delete GPOA activity "<strong>{{ $activity->name }}</strong>"?
	</p>
	<div class="submit-buttons">
		<button id="gpoa-activity_delete_close">Cancel</button>
		<form method="post" action="{{ $deleteActionRoute }}">
		@csrf
		@method('DELETE')
			<button>Delete</button>
		</form>
	</div>
</x-window>
</x-layout.user>
