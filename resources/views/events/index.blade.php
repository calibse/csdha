<x-layout.user index title="Events" class="events index">
	<div class="article">
		<x-alert/>
	@if ($events->isNotEmpty())
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
						<a href="{{ route('events.show', ['event' => $event->public_id]) }}">
                            {{ $event->gpoaActivity->name }}
                        </a>
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
		{{ $events->links('paginator.simple') }}
    @elseif (!$gpoa)
		<p>There is no active GPOA right now.</p>
	@else
		<p>No one has added anything yet</p>
	@endif
	</div>
</x-layout.user>
