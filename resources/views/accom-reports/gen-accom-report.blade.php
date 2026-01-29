<x-layout.user style="accom-report.scss" form :$backRoute title="Generate Accomplishment Report" class="accom-report generate form print">
<div class="article">
	<div class="print-control">
		<x-alert/>
		<form>
			<div class="inline">
				<p>
					<label>Start date</label>
				{{--
					<input {{ $hasLastJob && !$jobDone ? 'disabled' : null }} type="date" name="start_date" value="{{ $errors->any() ? old('start_date') : $startDate }}">
				--}}
					<input required placeholder="yyyy-mm-dd" pattern="^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$" type="date" name="start_date" value="{{ $errors->any() ? old('start_date') : $startDate }}">
				</p>
				<p>
					<label>End date</label>
				{{--
					<input {{ $hasLastJob && !$jobDone ? 'disabled' : null }} type="date" name="end_date" value="{{ $errors->any() ? old('end_date') : $endDate }}">
				--}}
					<input placeholder="yyyy-mm-dd" pattern="^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$" type="date" name="end_date" value="{{ $errors->any() ? old('end_date') : $endDate }}">
				</p>
				<p>
				{{--
					<button {{ $hasLastJob && !$jobDone ? 'disabled' : null }}>Generate</button>
				--}}
					<button>Generate</button>
				{{--
					<button form="cancel-form" {{ !$hasLastJob || $jobDone ? 'disabled' : null }}>Cancel</button>
				--}}
					<button 
					@if (!$hasMatch)
						disabled
					@endif
						type="button" id="print-button"
					>Print</button>
				</p>
			</div>
		</form>
	</div>
@if ($hasMatch)
	<x-accom-report :$events :$editors :$approved :$president />
{{--
@if ($fileRoute)
	<div class="pdf-document">
		<figure>
			<div class="pdf-file">
				<object data="{{ rtrim(url('/'), '/') . $fileRoute }}">
					<iframe src="/viewerjs/#..{{ $fileRoute}}">
						<p>
							Preview of this file is unsupported. You may download
							this file <a href="{{ rtrim(url('/'), '/') . $fileRoute }}">here</a>.
						</p>
					</iframe>
				</object>
			</div>
			<figcaption>
				<div class="caption">Accomplishment Report</div>
			</figcaption>
		</figure>
	</div>
@elseif ($hasLastJob && !$jobDone)
	<p class="has-icon">
		<img class="icon" src="{{ asset('icon/small/light/hourglass-medium.png') }}">
                <span class="text">
			{{ $prepareMessage }}
		</span>
	</p>
--}}
@elseif (!$errors->any() && $hasApproved && $hasInput)
	<p>No records available to generate.</p>
@elseif (!$hasApproved)
	<p>There are no approved accomplishment reports yet.</p>
@endif
</div>
<form id="cancel-form" style="display: none;" action="{{ $cancelFormAction }}">
</form>
</x-layout.user>
