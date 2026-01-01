<x-layout.user style="gpoa-report.scss" :$backRoute title="GPOA Report" class="gpoa print">
<x-slot:toolbar>
	<button id="print-button">
		<img class="icon" src="{{ asset('icon/light/printer.svg') }}">
		<span class="text">Print</span>
	</button>
</x-slot>
<div class="article">
	<div class="print-view">
		<div id="page-header">
			<div class="logos">
				<span class="logo">
					<img src="{{ asset('storage/university-logo.png') }}">
				</span>
				<span class="logo">
					<img src="{{ asset('storage/organization-logo.png') }}">
				</span>
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
				<col style="width: 4%"><!-- Number -->
				<col style="width: 9%"><!-- Name -->
				<col style="width: 5%"><!-- Date -->
				<col style="width: 20%"><!-- Objectives -->
				<col style="width: 11%"><!-- Participants -->
				<col style="width: 10%"><!-- Type -->
				<col style="width: 9%"><!-- Partnership -->
				<col style="width: 8%"><!-- Budget -->
				<col style="width: 6%"><!-- Fund -->
				<col style="width: 5%"><!-- Mode -->
				<col style="width: 13%"><!-- Event Head -->
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
				@foreach (range(1, 10) as $i)
				<tr>
					<td class="row-number">{{ ++$actCount . '.' }}</td>
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
			@endforeach
			</tbody>
		</table>
		<div class="signatures-page">
			<div class="column">
				<div class="content-block">
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
			</div>
			<div class="column">
				<div class="content-block">
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
