<x-layout.user content-view :$backRoute class="events" title="Event links">
<x-slot:toolbar>
	<a 
	@can ('update', $event)
		href="{{ $createRoute }}"
	@endcan
	>
		<img class="icon" src="{{ asset('icon/light/plus.png') }}">
		<span class="text">Add link</span>
	</a>
</x-slot:toolbar>
<div class="article">
	<x-alert/>
@if ($links->isNotEmpty())
	<ul class="item-list">
	@foreach ($links as $link)
		<li class="item">
			<span class="content"><a href="{{ $link->url }}" title="{{ $link->url }}">{{ $link->name }}</a></span>
			<span class="context-menu">
				<form method="get" action="{{ route('events.links.confirm-destroy', ['event' => $event->public_id, 'link' => $link->id ]) }}">
					<button
					@cannot ('update', $event)
						disabled
					@endcannot
					>Delete</button>
				</form>
			</span>
		</li>
	@endforeach
	</ul>
@else
	<p>Nothing here yet.</p>
@endif
</div>
</x-layout.user>
