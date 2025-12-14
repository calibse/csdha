<x-layout.user content-view :$backRoute class="settings" title="Edit GPOA Modes">
<div class="article">
	<x-alert/>
@if ($modes->isNotEmpty())
	<ul class="item-list" id="gpoa-mode-items">
	@foreach ($modes as $mode)
		<li class="item">
			<span class="content" id="gpoa-mode-{{ $mode->id }}">{{ $mode->name }}</span>
			<span class="context-menu">
				<form method="get" action="{{ route('settings.gpoa-activities.modes.confirm-destroy', ['mode' => $mode->id]) }}"> 
					<button {{ auth()->user()->cannot('delete', $mode) ? 'disabled' : null }} id="gpoa-mode-{{ $mode->id }}_delete-button" data-action="route('settings.gpoa-activities.modes.destroy, ['mode' => $mode->id]) }}">Delete</button>
				</form>
			</span>
		</li>
	@endforeach
	</ul>
@else
	<p>Nothing here yet.</p>
@endif
</div>
<x-window class="form" id="gpoa-mode_delete" title="Delete GPOA Mode">
        <p>
                Are you sure you want to delete GPOA mode "<strong id="gpoa-mode_delete-content"></strong>"?
        </p>
        <div class="submit-buttons">
                <button id="gpoa-mode_delete_close">Cancel</button>
                <button form="delete-form">Delete</button>
        </div>
        <form id="delete-form" method="post">
        @method('DELETE')
        @csrf
        </form>
</x-window>
</x-layout.user>
