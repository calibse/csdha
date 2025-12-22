<x-layout.user :$backRoute title="GPOA Report" class="gpoa print">
<x-slot:toolbar>
	<button id="print-button">
		<span class="text">Print</span>
	</button>
</x-slot>
<div class="article">
	<div id="page-header">
		<div class="logos">
			<div class="logo">
				<img src="{{ asset('storage/university-logo.png') }}">
			</div>
			<div class="logo">
				<img src="{{ asset('storage/organization-logo.png') }}">
			</div>
		</div>
		<div class="text">
			<p class="country">Republic of the Philippines</p>
			<p class="school">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</p>
			<p class="campus">TAGUIG CITY CAMPUS</p>
			<p class="org-name">COMPUTER SOCIETY</p>
		</div>
	</div>
	<div class="header">
		<p>GENERAL PLAN OF ACTIVITIES</p>
		<p>{{ $gpoa->academicPeriod?->term?->label }}
			A.Y. {{ $gpoa->academicPeriod?->year_label }}
		</p>
		<p>PUP-TAGUIG COMPUTER SOCIETY</p>
	</div>
	<table>
		<colgroup>
			<col style="width: 1.3cm"><!-- Number -->
			<col style="width: 3cm"><!-- Name -->
			<col style="width: 2cm"><!-- Date -->
			<col style="width: 5cm"><!-- Objectives -->
			<col style="width: 3cm"><!-- Participants -->
			<col style="width: 3cm"><!-- Type -->
			<col style="width: 2cm"><!-- Partnership -->
			<col style="width: 2cm"><!-- Budget -->
			<col style="width: 2cm"><!-- Fund -->
			<col style="width: 2cm"><!-- Mode -->
			<col style="width: 2.5cm"><!-- Event Head -->
		</colgroup>
		<thead>
			<tr>
				<th>No.</th>
				<th>Name of Activity</th>
				<th>Date</th>
				<th>Objectives</th>
				<th>Participant(s)/Beneficiary(ies) (indicate the number)</th>
				<th>Type of Activity</th>
				<th>Partnership</th>
				<th>Proposed Budget</th>
				<th>Source of Fund</th>
				<th>Mode</th>
				<th>Event Head</th>
			</tr>
		</thead>
		<tbody>
@php
$actCount = 0;
@endphp
		@foreach($activities as $activity)
			<tr>
				<td>{{ ++$actCount . '.' }}</td>
				<td>{{ $activity->name }}</td>
				<td>{{ $activity->date }}</td>
				<td>{{ $activity->objectives }}</td>
				<td>{{ $activity->participants }}</td>
				<td>{{ $activity->type }}</td>
				<td>{{ $activity->partnership_type }}</td>
				<td>{{ $activity->proposed_budget }}</td>
				<td>{{ $activity->fund_source }}</td>
				<td>{{ $activity->mode }}</td>
				<td>
					<ul>
					@foreach ($activity->eventHeadsOnly as $eventHead)
						<li>{{ $eventHead?->full_name }}</li>
					@endforeach
					</ul>
@php
$coheads = $activity->coheads
@endphp
				@if ($coheads->isNotEmpty())
					<p>Co-head:</p>
					<ul>
					@foreach ($coheads as $cohead)
						<li>{{ $cohead?->full_name }}</li>
					@endforeach
					</ul>
				@endif
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
	<div class="signatures-page">
		<div class="column">
		{{--
			<p>Prepared by:</p>
			<ul class="person-list">
				<li>
					<div class="signature"></div>
						<p class="name">{{ $adviser?->full_name }}</p>
						<p class="position">{{ $adviser?->position?->name }}, PUP-Taguig Computer Society</p>
				</li>
				<li>
					<div class="signature"></div>
						<p class="name">{{ $president?->full_name }}</p>
						<p class="position">{{ $president?->position?->name }}, PUP-Taguig Computer Society</p>
				</li>
			</ul>
			--}}
			<p>Noted by:</p>
			<ul class="person-list">
				<li>
					<div class="signature"></div>
						<p class="name">{{ $adviser?->full_name }}</p>
						<p class="position">{{ $adviser?->position?->name }}, PUP-Taguig Computer Society</p>
				</li>
			</ul>
		</div>
		<div class="column">
			<p>Approved by:</p>
			<ul class="person-list">
				<li>
					<div class="signature"></div>
				{{--
					<p class="name">Asst. Prof. Bernadette L. Canlas</p>
				--}}
					<p class="name">{{ $academicPeriod->head_of_student_services }}</p>
					<p class="position">Head of Student Services</p>
				</li>
				<li>
					<div class="signature"></div>
				{{--
					<p class="name">Dr. Marissa B. Ferrer</p>
				--}}
					<p class="name">{{ $academicPeriod->branch_director }}</p>
					<p class="position">PUP-Taguig Branch Director</p>
				</li>
			</ul>
		</div>
	</div>


{{--
@if (!$hasApproved)
<p>There are no approved activities yet.</p>
@elseif (!$updated)
<p class="has-icon">
<img class="icon" src="{{ asset('icon/small/light/hourglass-medium.png') }}">
<span class="text">
{{ $updateMessage }}
</span>
</p>
@elseif ($fileRoute)
<div class="pdf-document">
<figure>
<div class="pdf-file">
<object data="{{ rtrim(url('/'), '/') . $fileRoute}}">
<iframe src="/viewerjs/#..{{ $fileRoute}}">
<p>
Preview of this file is unsupported. You may download this 
file <a href="{{ rtrim(url('/'), '/') . $fileRoute }}">here</a>.
</p>
</iframe>
</object>
</div>
<figcaption>
<div class="caption">GPOA Report</div>
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
--}}
</div>
</x-layout.user>
