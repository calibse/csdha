<x-layout.user form :$backRoute title="Generate Accomplishment Report" class="accom-report generate form">
<article class="article">
	<div class="pdf-control">
		<x-alert/>
		<form>
			<div class="inline">
				<p>
					<label>Start date</label>
					<input {{ $hasLastJob && !$jobDone ? 'disabled' : null }} type="date" name="start_date" value="{{ $errors->any() ? old('start_date') : $startDate }}">
				</p>
				<p>
					<label>End date</label>
					<input {{ $hasLastJob && !$jobDone ? 'disabled' : null }} type="date" name="end_date" value="{{ $errors->any() ? old('end_date') : $endDate }}">
				</p>
				<p>
					<button {{ $hasLastJob && !$jobDone ? 'disabled' : null }}>Generate</button>
					<button form="cancel-form" {{ !$hasLastJob || $jobDone ? 'disabled' : null }}>Cancel</button>
				</p>
			</div>
		</form>
	</div>
@if ($fileRoute)
	<figure class="pdf-document">
		<div class="pdf-file">
			<object data="{{ $fileRoute }}" type="application/pdf">
				<p>
					Preview of this file is unsupported. You may download
					this file <a href="{{ $fileRoute }}">here</a>.
				</p>
			</object>
		</div>
		<figcaption class="caption">Accomplishment Report</figcaption>
	</figure>
@elseif ($hasLastJob && !$jobDone)
	<p>{{ $prepareMessage }}</p>
@elseif (!$errors->any() && $hasApproved && $hasInput)
	<p>No records available to generate.</p>
@elseif (!$hasApproved)
	<p>There are no approved accomplishment reports yet.</p>
@endif
</article>
<form id="cancel-form" style="display: none;" action="{{ $cancelFormAction }}">
</form>
</x-layout.user>
