<x-layout.user content-view :$backRoute class="settings form" title="Add Student Section">
<div class="article">
	<x-alert error-bag="student-section_create" />
	<form method="post" action="{{ $formAction }}">
	@csrf
		<p>
			<label for="section">Section</label>
			<input id="section" name="section">
		</p>
		<p class="form-submit">
			<button>Add</button>
		</p>
	</form>
</div>
</x-layout>
