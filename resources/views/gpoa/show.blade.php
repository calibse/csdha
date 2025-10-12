<x-layout.user :$backRoute class="gpoa" title="Old GPOA">
	<div class="article">
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
		<p><a href="#">GPOA Report</a></p>
		<p><a href="#">Accomplishment Report</a></p>
	</div>
</x-layout>

