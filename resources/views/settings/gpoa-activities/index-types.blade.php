<x-layout.user content-view :$backRoute class="settings" title="Edit GPOA Types">
<div class="article">
	<x-alert/>
@if ($types->isNotEmpty())
	<ul class="item-list">
	@foreach ($types as $type)
		<li class="item">
			<span class="content">{{ $type->name }}</span>
			<span class="context-menu">
				<form method="get" action="{{ route('settings.gpoa-activities.types.confirm-destroy', ['type' => $type->id]) }}"> 
					<button {{ auth()->user()->cannot('delete', $type) ? 'disabled' : null }} >Delete</button>
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
