<x-layout.user content-view :$backRoute class="settings form" title="Add Student Course">
<div class="article">
	<x-alert error-bag="student-course_create" />
	<form method="post" action="{{ $formAction }}">
	@csrf
		<p>
			<label for="name">Name</label>
			<input required maxlength="255" id="name" name="name">
		</p>
		<p>
			<label for="acronym">Acronym</label>
			<input required maxlength="8" id="acronym" name="acronym">
		</p>
		<p class="form-submit">
			<button>Add</button>
		</p>
	</form>
</div>
</x-layout>
