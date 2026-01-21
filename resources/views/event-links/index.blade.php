<x-layout.user content-view :$backRoute class="events" title="Event links">
<x-slot:toolbar>
	<a id="event-link_create-button"
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
	<ul id="event-link-items" class="item-list">
	@foreach ($links as $link)
		<li class="item">
			<span id="event-link-{{ $link->id }}" class="content"><a href="{{ $link->url }}" title="{{ $link->url }}">{{ $link->name }}</a></span>
			<span class="context-menu">
				<form action="{{ route('events.links.confirm-destroy', ['event' => $event->public_id, 'link' => $link->id ]) }}">
					<input type="hidden" id="event-link-{{ $link->id }}_delete-link" value="{{ route('events.links.destroy', ['event' => $event->public_id, 'link' => $link->id]) }}">
					<button id="event-link-{{ $link->id }}_delete-button" type="submit"
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
<x-window class="form" id="event-link_create" title="Add event link">
	<form method="post" action="{{ $addFormAction }}">
	@csrf
		<p>
			<label for="name">Name</label>
			<input required maxlength="255" id="name" name="name" value="{{ old('name') }}">
		</p>
		<p>
			<label for="url">URL</label>
			<input required maxlength="2000" id="url" name="url" value="{{ old('url') }}">
		</p>
		<p class="form-submit">
			<button type="button" id="event-link_create_close">Cancel</button>
			<button>Add</button>
		</p>
	</form>
</x-window>
<x-window class="form" id="event-link_delete" title="Add event link">
	<p>
		Are you sure you want to delete event link "<strong id="event-link_delete-content"></strong>"?
	</p>
	<div class="submit-buttons">
		<button id="event-link_delete_close">Cancel</button>
		<form method="post">
		@method('DELETE')
		@csrf
			<button>Delete</button>
		</form>
	</div>
</x-window>
</x-layout.user>
