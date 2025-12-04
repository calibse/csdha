@use('App\Services\Format')
<x-layout.user index form title="Events" class="form events index">
<div class="article">
	<div class="view-links">
		<div class="content-block">
			<p class="{{ Format::currentRoute($upcomingRoute) ? 'current-view' : null }}">
				<a href="{{ $upcomingRoute }}">Upcoming</a>
			</p><!-- --><p class="{{ Format::currentRoute($completedRoute) ? 'current-view' : null }}">
				<a href="{{ $completedRoute }}">Completed</a>
			</p>
		</div>
	</div>
	<x-alert/>
@if ($events->isNotEmpty())
	<div class="article-list">
	@foreach ($events as $event)
		<div class="event-item">
			<div class="banner" style="background-color: {{ $event->banner_placeholder_color }};">
			@if ($event->banner_filepath)
				<div class="content-block">
						<img src="{{ route('events.banner.show', ['event' => $event->public_id, 'file' => basename($event->banner_filepath)]) }}">
				</div>
			@endif
			</div>
			<div class="content-block">
				<h2 class="title">
					<a href="{{ route('events.show', ['event' => $event->public_id]) }}">{{ $event->gpoaActivity->name }}</a>
				</h2>
				<p class="date">
					<img class="icon" src="{{ asset('icon/small/light/calendar-dots.svg') }}">
					<span class="text">
					@if (!$event->dates()->exists())
						<em>No date.</em>
					@elseif ($event->is_ongoing)
						{{ $event->dates()->ongoing()->orderBy('date', 'desc')->orderBy('start_time')->first()->dateFmt }}
					@elseif ($event->is_upcoming)
						{{ $event->dates()->upcoming()->first()->dateFmt }}
					@else
						{{ $event->dates()->orderBy('date', 'desc')->orderBy('end_time', 'desc')->first()->dateFmt }}
					@endif
					</span>
				</p>
				<p class="time">
					<img class="icon" src="{{ asset('icon/small/light/clock.svg') }}">
					<span class="text">
					@if (!$event->dates()->exists())
						<em>No time.</em>
					@elseif ($event->is_ongoing)
						{{ $event->dates()->ongoing()->orderBy('date', 'desc')->orderBy('start_time')->first()->fullTime }}
					@elseif ($event->is_upcoming)
						{{ $event->dates()->upcoming()->first()->fullTime }}
					@else
						{{ $event->dates()->orderBy('date', 'desc')->orderBy('end_time', 'desc')->first()->fullTime }}
					@endif
					</span>
				</p>
				<p class="description">
				@if ($event->description)
					@if ($event->is_ongoing)
					<img class="inline-icon" src="{{ asset('icon/small/light/circle-red.svg') }}">
					Ongoing -- 
					@endif
					{{ $event->description }}
				@else
					@if ($event->is_ongoing)
					<img class="inline-icon" src="{{ asset('icon/small/light/circle-red.svg') }}">
					Ongoing
					@else
					<em>No description.</em>
					@endif
				@endif
				</p>
			</div>
		</div>
	@endforeach
	</div>
	{{ $events->links('paginator.simple') }}
@elseif (!$gpoa)
	<p>There is no active GPOA right now.</p>
@else
	<p>No one has added anything yet</p>
@endif
</div>
{{--
	<table class="table-3">
		<colgroup>
			<col style="width: 30%">
			<col style="width: 20%">
			<col style="width: 50%">
		</colgroup>
		<thead>
			<tr>
				<th>Name</th>
				<th>Status</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($events as $event)
			<tr>
				<td>
					<a href="{{ route('events.show', ['event' => $event->public_id]) }}">{{ $event->gpoaActivity->name }}</a>
				</td>
				<td>{{ $event->status }}</td>
				<td>
				@if ($event->description)
					{{ $event->description }}
				@else
					<i>No description.</i>
				@endif
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
--}}
</x-layout.user>
