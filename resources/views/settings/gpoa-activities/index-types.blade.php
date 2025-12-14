<x-layout.user content-view :$backRoute class="settings" title="Edit GPOA Types">
<div class="article">
	<x-alert/>
@if ($types->isNotEmpty())
	<ul class="item-list" id="gpoa-type-items">
	@foreach ($types as $type)
		<li class="item">
			<span class="content" id="gpoa-type-{{ $type->id }}">{{ $type->name }}</span>
			<span class="context-menu">
				<form method="get" action="{{ route('settings.gpoa-activities.types.confirm-destroy', ['type' => $type->id]) }}"> 
					<button {{ auth()->user()->cannot('delete', $type) ? 'disabled' : null }} id="gpoa-type-{{ $type->id }}_delete-button" data-action="{{ route('settings.gpoa-activities.types.destroy', ['type' => $type->id]) }}">Delete</button>
				</form>
			</span>
		</li>
	@endforeach
	</ul>
@else
	<p>Nothing here yet.</p>
@endif
</div>
<x-window class="form" id="gpoa-type_delete" title="Delete GPOA type">
        <p>
                Are you sure you want to delete GPOA type "<strong id="gpoa-type_delete-content"></strong>"?
        </p>
        <div class="submit-buttons">
                <button id="gpoa-type_delete_close">Cancel</button>
                <button form="delete-form">Delete</button>
        </div>
        <form id="delete-form" method="post">
        @method('DELETE')
        @csrf
        </form>
</x-window>
</x-layout.user>
