<x-layout.user content-view :$backRoute class="settings" title="Edit Student Year Levels">
	<x-slot:toolbar>
		<a 
                @can ('create', 'App\Models\StudentYear')
		href="{{ $createRoute }}"
		@endcan
		>
			<img class="icon" src="{{ asset('icon/light/plus-circle-duotone.png') }}">

			<span class="text">Add student year level</span>
		</a>
	</x-slot:toolbar>
	<div class="article">
		<x-alert/>
		<ul class="item-list">
		@foreach ($years as $year)
			<li class="item">
				<span class="content">{{ "{$year->label} ({$year->year})" }}</span>
				<span class="context-menu">
					<form method="get" action="{{ route('settings.students.years.confirm-destroy', ['year' => $year->id]) }}"> 
                                                <button {{ auth()->user()->cannot('delete', $year) ? 'disabled' : null }} >Delete</button>

					</form>
				</span>
			</li>
		@endforeach
		</ul>
	</div>
</x-layout.user>
