<p class="infobox">
	<a href="{{ $gpoaRoute }}">
		<span class="legend">Pending Activities</span>
		<span class="value" id="home-content_pending-activity-count">
			{{ $pendingGpoaActivityCount }}
		</span>
	</a>
</p>
<p class="infobox">
	<a href="{{ $eventsRoute }}">
		<span class="legend">Upcoming Events</span>
		<span class="value" id="home-content_upcoming-event-count">
			{{ $upcomingEventCount }}
		</span>
	</a>
</p>
<p class="infobox">
	<a href="{{ $accomReportsRoute }}">
		<span class="legend">Pending Accom. Reports</span>
		<span class="value" id="home-content_pending-accom-report-count">
			{{ $pendingAccomReportCount }}
		</span>
	</a>
</p>
