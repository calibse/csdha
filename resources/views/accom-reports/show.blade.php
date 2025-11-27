<x-layout.user has-toolbar form :$backRoute title="Accomplishment Report" class="accom-reports accom-report form">
<x-slot:toolbar>
@if ($actions['submit'])
	<a
	@can('submitAccomReport', $event)
		href="{{ $submitRoute }}"
	@endcan
	>
		<img class="icon" src="{{ asset('icon/light/arrow-bend-down-right.png') }}">
		<span class="text">Submit</span>
	</a>
@endif
@if ($actions['return'])
	<a
	@can('returnAccomReport', $event)
		href="{{ $returnRoute }}"
	@endcan
	>
		<img class="icon" src="{{ asset('icon/light/arrow-u-down-left.png') }}">
		<span class="text">Return</span>
	</a>
@endif
@if ($actions['approve'])
	<a
	@can('approveAccomReport', $event)
		href="{{ $approveRoute }}"
	@endcan
	>
		<img class="icon" src="{{ asset('icon/light/check-circle.png') }}">
		<span class="text">Approve</span>
	</a>
@endif
	<a href="{{ $eventRoute }}">
		<img class="icon" src="{{ asset('icon/light/calendar.png') }}">

		<span class="text">View event</span>
	</a>
</x-slot:toolbar>
<article class="article has-item-full-content-wide">
	<x-alert/>
@if ($accomReport)
	<aside class="main-status item-full-content-wide">
		<p class="title">Status</p>
		<p>
			<img class="icon" src="{{ asset('icon/small/light/circle-notch.png') }}">
			<span class="text">
				{{ $accomReport->full_status }}
			</span>
		</p>
	@if ($accomReport?->comments)
		<div>
			<img class="icon" src="{{ asset('icon/small/light/chat-text.png') }}">

			<pre class="text">"{{ $accomReport?->comments }}"</pre>
		</div>
	@endif
	@if ($date)
		<p>
			<img class="icon" src="{{ asset('icon/small/light/calendar-dot.png') }}">
			<span class="text">
				{{ $date }}
			</span>
		</p>
	@endif
	</aside>
@endif
@if (!$updated && auth()->user()->can('makeAccomReport', $event))
	<p class="has-icon">
		<img class="icon" src="{{ asset('icon/small/light/hourglass-medium.png') }}">
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
		<img class="icon" src="{{ asset('icon/small/light/hourglass-medium.png') }}">
		<span class="text">
			{{ $prepareMessage }}
		</span>
	</p>
@endif
</article>
</x-layout.user>
