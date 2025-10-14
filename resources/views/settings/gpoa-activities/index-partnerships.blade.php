<x-layout.user :$backRoute class="settings" title="Edit GPOA Partnerships">
	<div class="article">
		<x-alert/>
		<ul class="item-list">
		@foreach ($partnerships as $partnership)
			<li class="item">
				<span class="content">{{ $partnership->name }}</span>
				<span class="context-menu">
					<form method="get" action="{{ route('settings.gpoa-activities.partnership-types.confirm-destroy', ['partnership' => $partnership->id]) }}"> 
						<button>Delete</button>
					</form>
				</span>
			</li>
		@endforeach
		</ul>
	</div>
</x-layout.user>
