<x-layout.user index title="Events" class="events index">
	<div class="table-block">
		<x-alert/>
	@if ($events->isNotEmpty())
        <table class="table-2">
            <colgroup>
                <col style="width: 30%">
                <col style="width: 70%">
            </colgroup>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($events as $event)
                <tr>
                    <td>
						<a href="{{ route('events.show', ['event' => $event->public_id]) }}">
                            {{ $event->gpoaActivity->name }}
                        </a>
                    </td>
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







        {{--
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
		</ul>
        --}}
		{{ $events->links('paginator.simple') }}
	@else
		<p>No one has added anything yet</p>
	@endif
	</div>
</x-layout.user>
