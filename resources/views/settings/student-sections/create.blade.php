<x-layout.user :$backRoute class="settings form" title="Add Student Section">
	<div class="article">
		<x-alert/>
		<form method="post" action="{{ $formAction }}">
		@csrf
			<p>
				<label for="student-section">Student Section</label>
				<input id="student-section" name="student_section">
			</p>
			<p class="form-submit">
				<button>Add</button>
			</p>
		</form>
	</div>
</x-layout>
