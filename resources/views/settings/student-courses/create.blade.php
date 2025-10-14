<x-layout.user :$backRoute class="settings form" title="Add Student Course">
	<div class="article">
		<x-alert/>
		<form method="post" action="{{ $formAction }}">
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
				<button>Add</button>
			</p>
		</form>
	</div>
</x-layout>
