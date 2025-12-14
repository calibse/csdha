<x-layout.user content-view :$backRoute class="settings" title="Edit Student Sections">
<x-slot:toolbar>
	<a 
	@can ('create', 'App\Models\StudentSection')
		id="student-section_create-button"
		href="{{ $createSectionRoute }}"
	@endcan
	>
		<img class="icon" src="{{ asset('icon/light/plus.png') }}">

		<span class="text">Add student section</span>
	</a>
</x-slot:toolbar>
<div class="article">
	<x-alert/>
@if ($sections->isNotEmpty())
	<ul class="item-list" id="student-section-items">
	@foreach ($sections as $section)
		<li class="item">
			<span id="student-section-{{ $section->id }}" class="content">{{ $section->section }}</span>
			<span class="context-menu">
				<form method="get" action="{{ route('settings.students.sections.confirm-destroy', ['section' => $section->id]) }}"> 
					<button id="student-section-{{ $section->id }}_delete-button" data-action="{{ route('settings.students.sections.destroy', ['section' => $section->id]) }}" {{ auth()->user()->cannot('delete', $section) ? 'disabled' : null }} >Delete</button>
				</form>
			</span>
		</li>
	@endforeach
	</ul>
@else
	<p>Nothing here yet.</p>
@endif
</div>
<x-window class="form" id="student-section_create" title="Add student section">
	<form method="post" action="{{ $createFormAction }}">
	@csrf
		<p>
			<label for="section">Section</label>
			<input id="section" name="section">
		</p>
		<p class="form-submit">
			<button type="button" id="student-section_create_close">Cancel</button>
			<button>Add</button>
		</p>
	</form>
</x-window>
<x-window class="form" id="student-section_delete" title="Delete student section">
	<p>
		Are you sure you want to delete student section "<strong id="student-section_delete-content"></strong>"?
	</p>
	<div class="submit-buttons">
		<button type="button" id="student-section_delete_close">Cancel</button>
		<button form="delete-form">Delete</button>
	</div>
	<form id="delete-form" method="post">
	@method('DELETE')
	@csrf
	</form>
</x-window>
</x-layout.user>
