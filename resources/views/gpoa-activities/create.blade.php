<x-layout.user :$backRoute title="{{ $activity ? 'Update' : 'Add' }} GPOA Activity" class="gpoa form">
<div class="article">
	<x-alert type="error"/>
	<form method="post" action="{{ $formAction }}">
	@if ($activity)
		@method('PUT')
	@endif
	@csrf
		<p>
			<label>Name of Activity</label>
			<input name="name" value="{{ $errors->any() ? old('name') : $activity?->name }}">
		</p>
		<p>
			<label>Start Date</label>
			<input type="date" name="start_date" value="{{ $errors->any() ? old('start_date') : $activity?->start_date?->toDateString() }}">
		</p>
		<p>
			<label>End Date (optional)</label>
			<input type="date" name="end_date" value="{{ $errors->any() ? old('end_date') : $activity?->end_date?->toDateString() }}">
		</p>
		<p>
			<label>Objectives</label>
			<textarea name="objectives">{{ $errors->any() ? old('objectives') : $activity?->objectives }}</textarea>
		</p>
		<p>
			<label>Participants Description</label>
			<input name="participants_description" value="{{ $errors->any() ? old('participants_description') : $activity?->participants }}">
		</p>
		<p>
			<label>Type of Activity</label>
			<input name="type_of_activity" list="activity_types" autocomplete="off" value="{{ $errors->any() ? old('type_of_activity') : $activity?->type }}">
			<datalist id="activity_types">
			@foreach ($activityTypes as $type)
				<option value="{{ $type->name }}">
			@endforeach
			</datalist>
		</p>
		<p>
			<label>Mode</label>
			<input name="mode" list="modes" autocomplete="off" value="{{ $errors->any() ? old('mode') : $activity?->mode }}">
			<datalist id="modes">
			@foreach ($modes as $mode)
				<option value="{{ $mode->name }}">
			@endforeach
			</datalist>
		</p>
		<p>
			<label>Partnership (optional)</label>
			<input name="partnership" list="partnership_types" autocomplete="off" value="{{ $errors->any() ? old('partnership') : $activity?->partnership_type }}">
			<datalist id="partnership_types">
			@foreach ($partnershipTypes as $type)
				<option value="{{ $type->name }}">
			@endforeach
			</datalist>
		</p>
		<p>
			<label>Proposed Budget (optional)</label>
			<input type="number" min="0" step="100" name="proposed_budget" value="{{ $errors->any() ? old('proposed_budget') : $activity?->proposed_budget }}">
		</p>
		<p>
			<label>Source of Fund (optional)</label>
			<input name="fund_source" list="fund_sources" autocomplete="off" value="{{ $errors->any() ? old('fund_source') : $activity?->fund_source }}">
			<datalist id="fund_sources">
			@foreach ($fundSources as $source)
				<option value="{{ $source->name }}">
			@endforeach
			</datalist>
		</p>
	@if (!$activity || ($activity && auth()->user()->can('updateEventHeads', $activity)))
		<p>
			<label>Event Head</label>
			<select multiple size="5" name="event_heads[]">
			@if (!$activity || ($activity && $authUserIsEventHead))
				<option disabled value="">{{ auth()->user()->full_name }} (Added)</option>
			@endif
				<option value="0" 
				@if ($errors->any())
					{{ in_array('0', old('event_heads') ?? []) ? 'selected' : null }}
				@else
					{{ $activity && $allAreEventHeads ? 'selected' : null }}
				@endif
				>
					All CSCB Officers
				</option>
			@foreach ($selectedEventHeads as $selectedEventHead)
				<option value="{{ $selectedEventHead->public_id }}" selected>{{ $selectedEventHead->full_name }}</option>
			@endforeach
			@foreach ($eventHeads as $eventHead)
				<option value="{{ $eventHead->public_id }}">{{ $eventHead->full_name }}</option>
			@endforeach
			</select>
		</p>
		<p>
			<label>Co-head (optional)</label>
			<select multiple size="5" name="coheads[]"> 
			@foreach ($selectedCoheads as $selectedCohead)
				<option value="{{ $selectedCohead->public_id }}" selected>{{ $selectedCohead->full_name }}</option>
			@endforeach
			@foreach ($coheads as $cohead)
				<option value="{{ $cohead->public_id }}">{{ $cohead->full_name }}</option>
			@endforeach
			</select>
		</p>
	@endif
		<p class="form-submit">
			<button>{{ $activity ? 'Update' : 'Add' }} Activity</button>
		</p>
	</form>
</div>
</x-layout.user>
