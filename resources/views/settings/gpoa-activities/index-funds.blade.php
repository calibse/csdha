<x-layout.user content-view :$backRoute class="settings" title="Edit GPOA Funds">
<div class="article">
	<x-alert/>
@if ($funds->isNotEmpty())
	<ul class="item-list" id="gpoa-fund-items">
	@foreach ($funds as $fund)
		<li class="item">
			<span class="content" id="gpoa-fund-{{ $fund->id }}">{{ $fund->name }}</span>
			<span class="context-menu">
				<form method="get" action="{{ route('settings.gpoa-activities.fund-sources.confirm-destroy', ['fund' => $fund->id]) }}"> 
					<button {{ auth()->user()->cannot('delete', $fund) ? 'disabled' : null }} id="gpoa-fund-{{ $fund->id }}_delete-button" data-action="{{ route('settings.gpoa-activities.fund-sources.destroy', ['fund' => $fund->id]) }}">Delete</button>
				</form>
			</span>
		</li>
	@endforeach
	</ul>
@else
	<p>Nothing here yet.</p>
@endif
</div>
<x-window class="form" id="gpoa-fund_delete" title="Delete GPOA fund">
        <p>
                Are you sure you want to delete GPOA fund "<strong id="gpoa-fund_delete-content"></strong>"?
        </p>
        <div class="submit-buttons">
                <button id="gpoa-fund_delete_close">Cancel</button>
                <button form="delete-form">Delete</button>
        </div>
        <form id="delete-form" method="post">
        @method('DELETE')
        @csrf
        </form>
</x-window>
</x-layout.user>
