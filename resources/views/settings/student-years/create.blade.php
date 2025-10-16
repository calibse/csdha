<x-layout.user :$backRoute class="settings form" title="Add Student Year Level">
	<div class="article">
		<x-alert/>
		<form method="post" action="{{ $formAction }}">
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
				<button>Add</button>
			</p>
		</form>
	</div>
</x-layout>
