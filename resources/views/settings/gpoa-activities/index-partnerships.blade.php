<x-layout.user content-view :$backRoute class="settings" title="Edit GPOA Partnerships">
<div class="article">
	<x-alert/>
@if ($partnerships->isNotEmpty())
	<ul class="item-list" id="gpoa-partnership-items">
	@foreach ($partnerships as $partnership)
		<li class="item">
			<span class="content" id="gpoa-partnership-{{ $partnership->id }}">{{ $partnership->name }}</span>
			<span class="context-menu">
				<form method="get" action="{{ route('settings.gpoa-activities.partnership-types.confirm-destroy', ['partnership' => $partnership->id]) }}"> 
					<button {{ auth()->user()->cannot('delete', $partnership) ? 'disabled' : null }} id="gpoa-partnership-{{ $partnership->id }}_delete-button" data-action="{{ route('settings.gpoa-activities.partnership-types.destroy', ['partnership' => $partnership->id]) }}">Delete</button>
				</form>
			</span>
		</li>
	@endforeach
	</ul>
@else
	<p>Nothing here yet.</p>
@endif
</div>
<x-window class="form" id="gpoa-partnership_delete" title="Delete GPOA partnership">
        <p>
                Are you sure you want to delete GPOA partnership "<strong id="gpoa-partnership_delete-content"></strong>"?
        </p>
        <div class="submit-buttons">
                <button id="gpoa-partnership_delete_close">Cancel</button>
                <button form="delete-form">Delete</button>
        </div>
        <form id="delete-form" method="post">
        @method('DELETE')
        @csrf
        </form>
</x-window>
</x-layout.user>
