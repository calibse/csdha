<x-layout.user content-view :$backRoute class="settings" title="Edit GPOA Funds">
<div class="article">
	<x-alert/>
@if ($funds->isNotEmpty())
	<ul class="item-list">
	@foreach ($funds as $fund)
		<li class="item">
			<span class="content">{{ $fund->name }}</span>
			<span class="context-menu">
				<form method="get" action="{{ route('settings.gpoa-activities.fund-sources.confirm-destroy', ['fund' => $fund->id]) }}"> 
					<button {{ auth()->user()->cannot('delete', $fund) ? 'disabled' : null }} >Delete</button>
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
