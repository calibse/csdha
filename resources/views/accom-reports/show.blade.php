<x-layout.user has-toolbar form :$backRoute title="Accomplishment Report" class="accom-reports accom-report form">
<x-slot:toolbar>
@if ($actions['submit'])
	<a 
	@can('submitAccomReport', $event)
		id="accom-report_submit-button" data-action="{{ $submitActionRoute }}" href="{{ $submitRoute }}"
	@endcan
	>
		<img class="icon" src="{{ asset('icon/light/arrow-bend-down-right.svg') }}">
		<span class="text">Submit</span>
	</a>
@endif
@if ($actions['return'])
	<a 
	@can('returnAccomReport', $event)
		href="{{ $returnRoute }}" id="accom-report_return-button" data-action="{{ $returnActionRoute }}"
	@endcan
	>
		<img class="icon" src="{{ asset('icon/light/arrow-u-down-left.svg') }}">
		<span class="text">Return</span>
	</a>
@endif
@if ($actions['approve'])
	<a 
	@can('approveAccomReport', $event)
		href="{{ $approveRoute }}" id="accom-report_approve-button" data-action="{{ $approveActionRoute }}"
	@endcan
	>
		<img class="icon" src="{{ asset('icon/light/check-circle.svg') }}">
		<span class="text">Approve</span>
	</a>
@endif
	<a href="{{ $eventRoute }}">
		<img class="icon" src="{{ asset('icon/light/calendar.svg') }}">

		<span class="text">View event</span>
	</a>
</x-slot:toolbar>
<div class="article has-item-full-content-wide">
	<x-alert/>
@if ($accomReport)
	<aside class="main-status item-full-content-wide">
		<div class="content-block">
			<p class="title">Status</p>
			<p>
				<img class="icon" src="{{ asset('icon/small/light/circle-notch.svg') }}">
				<span class="text">
					{{ $accomReport->full_status }}
				</span>
			</p>
		@if ($accomReport?->comments)
			<div>
				<img class="icon" src="{{ asset('icon/small/light/chat-text.svg') }}">

				<pre class="text">"{{ $accomReport?->comments }}"</pre>
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
		</div>
	</aside>
@endif
@if (!$updated && auth()->user()->can('makeAccomReport', $event))
	<p class="has-icon">
		<img class="icon" src="{{ asset('icon/small/light/hourglass-medium.svg') }}">
		<span class="text">
			{{ $updateMessage }}
		</span>
	</p>
@elseif ($fileRoute)
	@if (!$updated)
	<p>This document copy is outdated.</p>
	@endif
	<div class="pdf-document">
		<figure class="pdf-file">
			<iframe src="/viewerjs/#..{{ $fileRoute}}">
				<p>
					Preview of this file is unsupported. You may download this 
					file <a href="{{ rtrim(url('/'), '/') . $fileRoute }}">here</a>.
				</p>
			</iframe>
			<figcaption>
				<div class="caption">Accomplishment Report</div>
			</figcaption>
		</figure>
	</div>
@else
        <p class="has-icon">
		<img class="icon" src="{{ asset('icon/small/light/hourglass-medium.svg') }}">
		<span class="text">
			{{ $prepareMessage }}
		</span>
	</p>
@endif
</div>
<x-window id="accom-report_prepare" class="form" title="{{ $action ?? '[Action]'}} accom. report">
	<form method="post" action="{{ $formActionUrl }}">
	@method('PUT')
	@csrf
		<p>
			<label>Comments</label>
			<textarea name="comments"></textarea>
		</p>
		<p class="form-submit">
			<button id="accom-report_prepare_close" type="button">Cancel</button>
			<button id="accom-report_prepare-button">{{ $action ?? '[Action]' }}</button>
		</p>
	</form>
</x-window>
</x-layout.user>
