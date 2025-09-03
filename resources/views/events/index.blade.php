<x-layout.user index title="Events" class="events index">
	<article class="article">
		<x-alert/>
	@if ($events->isNotEmpty())
		<ul class="item-list-icon">
		@foreach ($events as $event)
			<li class="item">
				<div class="icon">
				@if ($event->cover_photo_filepath)
		            <img src="{{ route('events.showCoverPhoto', ['event' => $event->public_id, 'file' => basename($event->cover_photo_filepath) ]) }}">
	            @else
					<x-phosphor-calendar-fill/>
				@endif
				</div>
				<div class="content">
					<p class="title">
						<a href="{{ route('events.show', ['event' => $event->public_id]) }}">
							{{ mb_strimwidth($event->gpoaActivity->name, 0, 70, '...') }}
						</a>
					</p>
					<p class="subtitle">
						@if ($event->description)
						{{ mb_strimwidth($event->description, 0, 70, '...') }}
						@else 
						<i>No description yet.</i>
						@endif
					</p>
				</div>
			</li>
		@endforeach
		{{--
		@foreach (range(0, 10) as $n)
			<li class="item">
				<div class="icon">
					<x-phosphor-calendar-fill/>
				</div>
				<div class="content">
					<p class="title">
						Title
					</p>
					<p>Objectives</p>
				</div>
			</li>
		@endforeach
		--}}
		</ul>
		{{ $events->links('paginator.simple') }}
	@else
		<p>No one has added anything yet</p>
	@endif
	</article>
</x-layout.user>
