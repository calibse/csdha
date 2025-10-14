<x-layout.user :$backRoute class="settings" title="Edit GPOA Modes">
	<div class="article">
		<x-alert/>
		<ul class="item-list">
		@foreach ($modes as $mode)
			<li class="item">
				<span class="content">{{ $mode->name }}</span>
				<span class="context-menu">
					<form method="get" action="{{ route('settings.gpoa-activities.modes.confirm-destroy', ['mode' => $mode->id]) }}"> 
						<button>Delete</button>
					</form>
				</span>
			</li>
		@endforeach
		</ul>
	</div>
</x-layout.user>
