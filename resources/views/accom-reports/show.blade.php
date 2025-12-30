@use ('App\Services\Format')
<x-layout.user style="accom-report.scss" has-toolbar content-view :$backRoute title="Accomplishment Report" class="accom-reports accom-report print">
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
	<div class="main-status item-full-content-wide">
		<div class="content-block">
			<aside>
				<p class="title">Status</p>
				<p>
					<img class="icon" src="{{ asset("icon/small/light/circle-{$accomReport->status_color}.svg") }}">
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
			</aside>
		</div>
	</div>
@endif
	<div class="print-view">
		<div id="page-header" class="page-header">
			<div class="logos">
				<div class="logo">
					<img src="{{ asset('storage/university-logo.png') }}"/>
				</div>
				<div class="logo">
					<img src="{{ asset('storage/organization-logo.png') }}"/>
				</div>
			</div>
			<div class="text">
				<p class="country">Republic of the Philippines</p>
				<p class="school">Polytechnic University of the Philippines</p>
				<p class="campus">Taguig Campus</p>
				<p class="organization">Computer Society</p>
			</div>
		</div>

		<div id="cover-page" class="cover-page">
			<div class="cover-title">
					<p class="org-name">COMPUTER SOCIETY</p>
					<p class="school-name">PUP - TAGUIG</p>
			</div>
			<div class="info">
				<div class="credit">
					<p class="title">Prepared by:</p>
					<ul class="people">
					@foreach ($editors as $editor)
						<li>
							<p class="name">{{ strtoupper($editor->full_name) }}</p>
							<p>CS PUPT {{ $editor->position->name }}</p>
						</li>
					@endforeach
					</ul>
				</div>
			@if (true || $approved)
				<div class="credit">
					<p class="title">Approved By:</p>
					<ul class="people">
						<li>
							<p>{{ strtoupper($president->full_name) }}</p>
							<p>CS PUPT {{ $president->position->name }}</p>
						</li>
					</ul>
				</div>
			@endif
			</div>
		</div>
	@foreach ($events as $event)
		<div class="start-page">
			<div class="start-content">
				<div class="start-title">
					<p class="org-name">COMPUTER SOCIETY</p>
					<p class="school-name">PUP - TAGUIG</p>
				</div>
				<h1 class="event-name">{{ $event['activity']->name }}</h1>
				<p class="year">A.Y. {{ $event['activity']->gpoa->academicPeriod?->year_label }}</p>
			</div>
		</div>
		
		<main class="content">
		@php $i = 0; @endphp
			<h2 class="event-name">{{ $event['activity']->name }}</h2>
			<section class="date-section">
				<h3>{{ Format::roman(++$i) }}. DATE AND TIME</h3>
				<ul>
				@foreach ($event['event']->compactDates() as $date)
					<li>{{ $date }}</li>
				@endforeach
				</ul>
			</section>
			<section>
				<h3>{{ Format::roman(++$i) }}. VENUE</h3>
				<p>{{ $event['event']->venue }}</p>
			</section>
			<section>
				<h3>{{ Format::roman(++$i) }}. TYPE OF ACTIVITY</h3>
				<p>{{ $event['activity']->type }}</p>
			</section>
			<section>
				<h3>{{ Format::roman(++$i) }}. PARTICIPANTS</h3>
				<p>{{ $event['activity']->participants }}</p>
			</section>
			<section>
				<h3>{{ Format::roman(++$i) }}. OBJECTIVES</h3>
				<pre>{{ $event['activity']->objectives }}</pre>
			</section>
			<section>
				<h3>{{ Format::roman(++$i) }}. DESCRIPTION</h3>
				<pre>{{ $event['event']->description }}</pre>
			</section>
			<section>
				<h3>{{ Format::roman(++$i) }}. NARRATIVE</h3>
				<pre>{{ $event['event']->narrative }}</pre>
			</section>
		@if ($event['attendance']?->isNotEmpty())
			<section>
				<h3>{{ Format::roman(++$i) }}. ATTENDANCE</h3>
			@switch ($event['event']->participant_type)
			@case ('students')
				@switch ($event['attendanceView'])
		{{--
			@switch ('year')
		--}}
				@case ('student')
				<table>
					<thead>
						<tr>
							<th>Course & Section</th>
							<th>Attendees</th>
						</tr>
					</thead>
					<tbody>
					@foreach ($event['attendance'] as $attendee)
						<tr>
							<td>{{ $attendee->course_section }}</td>
							<td>{{ $attendee->full_name }}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
					@break
				@case ('year')
				<table>
					<thead>
						<tr>
							<th>Year</th>
							<th>Attendees</th>
						</tr>
					</thead>
					<tbody>
					@foreach ($event['attendance'] as $year => $count)
						<tr>
							<td>{{ $year }}</td>
							<td>{{ $count }}</td>
						</tr>
					@endforeach
					</tbody>
					<tfoot>
						<tr>
							<th>Total</th>
							<td>{{ $event['attendanceTotal'] }}</td>
						</tr>
					</tfoot>
				</table>
						@break
				@case ('program')
				<table>
					<thead>
						<tr>
							<th>Course & Year</th>
							<th>Attendees</th>
						</tr>
					</thead>
					<tbody>
					@foreach ($event['attendance'] as $program => $count)
						<tr>
							<td>{{ $program }}</td>
							<td>{{ $count }}</td>
						</tr>
					@endforeach
					</tbody>
					<tfoot>
						<tr>
							<th>Total</th>
							<td>{{ $event['attendanceTotal'] }}</td>
						</tr>
					</tfoot>
				</table>
					@break
				@endswitch
				@break
			@case ('officers')
				<table class="officers-attendance">
					<thead>
						<tr>
							<th>Position</th>
							<th>Name</th>
						</tr>
					</thead>
					<tbody>
					@foreach ($event['attendance'] as $officer)
					<tr>
						<td>{{ $officer->position->name }}</td>
						<td>{{ $officer->full_name }}</td>
					</tr>
					@endforeach
					</tbody>
				</table>
				@break
			@endswitch
			</section>
		@endif
		@if ($event['event']->accept_evaluation && $event['event']->evaluations()->exists())
			<section>
				<h3>{{ Format::roman(++$i) }}. EVALUATION</h3>
				<div class="list-section">
					<p>Scale:</p>
					<p>5 - Excellent | 4 - Good | 3 - Average | 2 - Fair | 1 - Poor</p>
					<ul>
						<li>Overall rating of the event</li>
						<li>The objectives of the presentation were clear</li>
						<li>Overall experience</li>
					</ul>
				</div>
			@if ($event['comments']->isNotEmpty())
				<div class="list-section">
					<p>What are your comments, feedback, or suggestions?</p>
					<ul class="comment-list">
					@foreach ($event['comments'] as $comment)
						<li><pre>{{ $comment }}</pre></li>
					@endforeach
					</ul>
				</div>
			@endif
			</section>
			<section>
				<h3>{{ Format::roman(++$i) }}.  INTERPRETATION OF THE EVALUATION</h3>
				<table class="evaluation-summary">
					<tr>
						<th>Overall Satisfaction: </td>
						<td>{{ $event['ratings']['os'] }}</td>
					</tr>
					<tr>
						<th>Content Relevance:</th>
						<td>{{ $event['ratings']['cr'] }}</td>
					</tr>
					<tr>
						<th>Speaker Effectiveness: </th>
						<td>{{ $event['ratings']['se'] }}</td>
					</tr>
					<tr>
						<th>Engagement Level: </th>
						<td>{{ $event['ratings']['el'] }}</td>
					</tr>
					<tr>
						<th>Duration:</th>
						<td>{{ $event['ratings']['du'] }}</td>
					</tr>
					<tr class="overall-score">
						<th>Overall:</th>
						<td>{{ $event['ratings']['overall'] }}</td>
					</tr>
				</table>
			</section>
		@endif
		@if ($event['event']->attachmentSets()->exists())
			<section class="attachment-section">
				<h3>{{ Format::roman(++$i) }}. ATTACHMENTS</h3>
		@php $skip = false; @endphp
			@foreach ($event['event']->attachmentSets()->orderBy('created_at', 'asc')->get() as $set)
				@foreach ($set->attachments()->orderBy('created_at', 'asc')->get() as $i => $attachment)
					@if ($skip)
		@php $skip = false; @endphp
						@continue;
					@endif
		@php $next = $set->attachments[$i + 1] ?? null; @endphp
				<div class="attachment">
					<img class="{{ $attachment->orientation }} {{ $attachment->full_width ? 'full-width' : null }}" src="storage/app/private/{{ $attachment->image_filepath }}">
				@if (!($attachment->standalone || $attachment->full_width) && in_array($attachment->orientation, ['portrait', 'square']) && $next && in_array($next->orientation, ['portrait', 'square']) && !($next->standalone || $next->full_width))
					<img class="{{ $next->orientation }}" src="storage/app/private/{{ $next->image_filepath }}">
		@php $skip = true; @endphp
				@endif
				</div>
				@endforeach
				<p class="caption">{{ $set->caption }}</p>
			@endforeach
			</section>
		@endif
		</main>
	@endforeach
	</div>


{{--
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
		<figure>
			<div class="pdf-file">
				<object data="{{ rtrim(url('/'), '/') . $fileRoute }}">
				<iframe src="/viewerjs/#..{{ $fileRoute}}">
					<p>
						Preview of this file is unsupported. You may download this 
						file <a href="{{ rtrim(url('/'), '/') . $fileRoute }}">here</a>.
					</p>
				</iframe>
				</object>
			</div>
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
--}}
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
