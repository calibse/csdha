<x-layout.user content-view :$backRoute class="settings" title="Edit Student Year Levels">
<x-slot:toolbar>
	<a 
	@can ('create', 'App\Models\StudentYear')
		href="{{ $createRoute }}"
		id="student-year-level_create-button"
	@endcan
	>
		<img class="icon" src="{{ asset('icon/light/plus.png') }}">

		<span class="text">Add student year level</span>
	</a>
</x-slot:toolbar>
<div class="article">
	<x-alert/>
@if ($years->isNotEmpty())
	<ul class="item-list" id="student-year-level-items">
	@foreach ($years as $year)
		<li class="item">
			<span class="content" id="student-year-level-{{ $year->id }}">{{ "{$year->label} ({$year->year})" }}</span>
			<span class="context-menu">
				<form method="get" action="{{ route('settings.students.years.confirm-destroy', ['year' => $year->id]) }}"> 
					<button {{ auth()->user()->cannot('delete', $year) ? 'disabled' : null }} id="student-year-level-{{ $year->id }}_delete-button" data-action="{{ route('settings.students.years.destroy', ['year' => $year->id]) }}">Delete</button>

				</form>
			</span>
		</li>
	@endforeach
	</ul>
@else
	<p>Nothing here yet.</p>
@endif
</div>
<x-window class="form" id="student-year-level_create" title="Add student year level">
        <form method="post" action="{{ $createFormAction }}">
        @csrf
                <p>
                        <label for="year-level">Year Level <small>(e.g. 2)</small></label>
                        <input id="year-level" name="year_level">
                </p>
                <p>
                        <label for="label">Label <small>(.e.g. 2nd year)</small></label>
                        <input id="label" name="label">
                </p>
                <p class="form-submit">
                        <button type="button" id="student-year-level_create_close">Cancel</button>
                        <button>Add</button>
                </p>
        </form>
</x-window>
<x-window class="form" id="student-year-level_delete" title="Delete student year level">
	<p>
		Are you sure you want to delete student year level "<strong id="student-year-level_delete-content" ></strong>"?
	</p>
	<div class="submit-buttons">
		<button id="student-year-level_delete_close" >Cancel</button>
		<button form="delete-form">Delete</button>
	</div>
	<form id="delete-form" method="post">
	@method('DELETE')
	@csrf
	</form>
</x-window>
</x-layout.user>
