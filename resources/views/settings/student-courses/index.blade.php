<x-layout.user content-view :$backRoute class="settings" title="Edit Student Courses">
<x-slot:toolbar>
	<a 
	@can ('create', 'App\Models\Course')
		id="student-course_create-button"
		href="{{ $createRoute }}"
	@endcan
	>
		<img class="icon" src="{{ asset('icon/light/plus.png') }}">
		<span class="text">Add student course</span>
	</a>
</x-slot:toolbar>
<div class="article">
	<x-alert/>
@if ($courses->isNotEmpty())
	<ul class="item-list" id="student-course-items">
	@foreach ($courses as $course)
		<li class="item">
			<span class="content" id="student-course-{{ $course->id }}">{{ "{$course->name} ({$course->acronym})"  }}</span>
			<span class="context-menu">
				<form method="get" action="{{ route('settings.students.courses.confirm-destroy', ['course' => $course->id]) }}"> 
					<button {{ auth()->user()->cannot('delete', $course) ? 'disabled' : null }} id="student-course-{{ $course->id }}_delete-button" data-action="{{ route('settings.students.courses.destroy', ['course' => $course->id]) }}">Delete</button>

				</form>
			</span>
		</li>
	@endforeach
	</ul>
@else
	<p>Nothing here yet.</p>
@endif
</div>
<x-window class="form" id="student-course_create" title="Add student course">
	<form method="post" action="{{ $createFormAction }}">
	@csrf
		<p>
			<label for="name">Name</label>
			<input id="name" name="name">
		</p>
		<p>
			<label for="acronym">Acronym</label>
			<input id="acronym" name="acronym">
		</p>
		<p class="form-submit">
			<button type="button" id="student-course_create_close">Cancel</button>
			<button>Add</button>
		</p>
	</form>
</x-window>
<x-window class="form" id="student-course_delete" title="Delete student course">
        <p>
                Are you sure you want to delete student course "<strong id="student-course_delete-content"></strong>"?
        </p>
        <div class="submit-buttons">
                <button id="student-course_delete_close">Cancel</button>
                <button form="delete-form">Delete</button>
        </div>
        <form id="delete-form" method="post">
        @method('DELETE')
        @csrf
        </form>
</x-window>
</x-layout.user>
