<div class="featbox">
	<div class="feat-controller" id="featured-1"></div>
	<div class="feat-controller" id="featured-2"></div>
	<div class="feat-controller" id="featured-3"></div>
	<div class="content-block">
	<div class="featbox-contents">
		<div style="background-color: lightblue;" class="featcontent">
			<x-home-feat-event/>
			<div class="content-siblings-links">
				<div class="next"><a href="#featured-3"></a></div>
				<div class="prev"><a href="#featured-2"></a></div>
			</div>
		</div><!-- --><div 
			style="background-color: lightgreen;" class="featcontent">
			<p>Hello2</p>
			<div class="content-siblings-links">
				<div class="next"><a href="#featured-1"></a></div>
				<div class="prev"><a href="#featured-3"></a></div>
			</div>
		</div><!-- --><div 
			style="background-color: lightblue;" class="featcontent">
			<p>Hello3</p>
			<div class="content-siblings-links">
				<div class="next"><a href="#featured-2"></a></div>
				<div class="prev"><a href="#featured-1"></a></div>
			</div>
		</div>
	</div>
	</div>
	<div class="content-nav-links featbox-indicator">
		<a 
			class="featured1-dot" href="#featured-1"></a><a 
			class="featured2-dot" href="#featured-2"></a><a 
			class="featured3-dot" href="#featured-3"></a
		>
	</div>
</div>

<div class="introbox">
	<p class="title">CSDHA is the platform for CS activities</p>
	<p>Manage event planning, attendance, feedback, and accomplishment reports, in one place.</p>
</div>

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
