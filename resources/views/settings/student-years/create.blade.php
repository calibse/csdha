<x-layout.user content-view :$backRoute class="settings form" title="Add Student Year Level">
<div class="article">
	<x-alert error-bag="student-year-level_create" />
	<form method="post" action="{{ $formAction }}">
	@csrf
		<p>
			<label for="year-level">Year Level <small>(e.g. 2)</small></label>
			<input required maxlength="4" id="year-level" name="year_level">
		</p>
		<p>
			<label for="label">Label <small>(.e.g. 2nd year)</small></label>
			<input required maxlength="15" id="label" name="label">
		</p>
		<p class="form-submit">
			<button>Add</button>
		</p>
	</form>
</div>
</x-layout>
