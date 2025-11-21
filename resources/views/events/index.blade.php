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
			<h2 class="title">
				<a href="{{ route('events.show', ['event' => $event->public_id]) }}">{{ $event->gpoaActivity->name }}</a>
			</h2>
		{{--
			<p class="date">{{ $event->status }}</p>
		--}}
		@if ($event->dates()->exists())
			<p class="date">
				<img class="icon" src="{{ asset('icon/small/light/calendar-dots.png') }}">
				<span class="text">
				@if ($event->is_ongoing)
					{{ $event->dates()->ongoing()->orderBy('date', 'desc')->orderBy('start_time')->first()->dateFmt }}
				@else
					{{ $event->dates()->orderBy('date', 'desc')->orderBy('start_time')->first()->dateFmt }}
				@endif
				</span>
			</p>
			<p class="time">
				<img class="icon" src="{{ asset('icon/small/light/clock.png') }}">
				<span class="text">
				@if ($event->is_ongoing)
					{{ $event->dates()->ongoing()->orderBy('date', 'desc')->orderBy('start_time')->first()->fullTime }}
				@else
					{{ $event->dates()->orderBy('date', 'desc')->orderBy('start_time')->first()->fullTime }}
				@endif
				</span>
			</p>
		@endif
			<p class="description">
			@if ($event->description)
				@if ($event->is_ongoing)
				(Ongoing)
				@endif
				{{ $event->description }}
			@else
				@if ($event->is_ongoing)
				Ongoing
				@else
				<i>No description.</i>
				@endif
			@endif
			</p>
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
