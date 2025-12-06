<div class="featbox">
	<div class="feat-controller" id="featured-1"></div>
	<div class="feat-controller" id="featured-2"></div>
	<div class="feat-controller" id="featured-3"></div>
	<div class="content-block">
		<div class="featbox-contents" style="{{ $featStatus === 'none' ? 'animation: none;' : null }}">
		@if ($featStatus === 'full')
			<div> 	
			@foreach ($featContents as $featContent)
			</div><!-- --><div class="featcontent">
				<x-dynamic-component :component="$featContent['view']" :model="$featContent['model']" :next="$featContent['next_link']" :prev="$featContent['prev_link']" />
			@endforeach
			@if (count($featContents) === 2)
			</div><!-- --><div class="featcontent">
				<x-home-feat-welcome next="#featured-1" prev="#featured-2" />
			</div>
			@else
			</div>
			@endif
		@elseif ($featStatus === 'partial')
			<div> 	
			@foreach ($featContents as $featContent)
			</div><!-- --><div class="featcontent">
				<x-dynamic-component :component="$featContent['view']" :model="$featContent['model']" :next="$featContent['next_link']" :prev="$featContent['prev_link']" />
			@endforeach
			</div><!--
			--><div class="featcontent">
				<x-home-feat-welcome next="#featured-1" prev="#featured-2" />
			</div>
		@else
			<div class="featcontent">
				<x-home-feat-welcome />
			</div>
		@endif
		</div>
	</div>
@if ($featStatus !== 'none')
	<div class="featbox-nav-hint">
		<img class="next" src="{{ asset('icon/light/caret-right.svg') }}">
		<img class="prev" src="{{ asset('icon/light/caret-left.svg') }}">
	</div>
	<div class="content-nav-links featbox-indicator">
		<a 
			class="featured1-dot" href="#featured-1"></a><a 
			class="featured2-dot" href="#featured-2"></a><a 
			class="featured3-dot" href="#featured-3"></a
		>
	</div>
@endif
</div>

@if ($featStatus !== 'none')
<div class="introbox">
	<p class="title">CSDHA is the platform for CS activities</p>
	<p class="welcome-message">Manage event planning, attendance, feedback, and accomplishment reports, in one place.</p>
</div>
@endif

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
