<x-layout.user :$backRoute class="gpoa" title="Closed GPOA">
	<div class="article">
		<p><a href="{{ $reportRoute }}">GPOA Report</a></p>
	@if ($accomReportCount)
		<p><a href="{{ $accomReportRoute }}">Accomplishment Report</a></p>
	@endif
		<dl>
			<dt>Created by</dt>
			<dd>{{ $createdBy }}</dd>
			<dt>Closed by</dt>
			<dd>{{ $closedBy }}</dd>
			<dt>Academic Period</dt>
			<dd>{{ $academicPeriod }}</dd>
			<dt>Number of approved activities</dt>	
			<dd>{{ $activityCount }} activities</dd>
			<dt>Number of approved accomplishment reports</dt>
			<dd>{{ $accomReportCount }} accomplishment reports</dd>
		</dl>
	</div>
</x-layout>

