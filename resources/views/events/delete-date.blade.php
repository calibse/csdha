<x-layout.user :$backRoute class="events form" title="Delete Date">
<article class="article">
	<p>
	@if ($date->attendees->isNotEmpty())
		Attendance records in this date will be destroyed.
	@endif
		Are you sure you want to delete this date <strong><time>{{ $date->full_date }}</time></strong>?
	</p>
	<div class="submit-buttons">
		<form action="{{ $backRoute }}">
			<button>Cancel</button>
		</form>
		<form method="POST" action="{{ $formAction }}">
		@method('DELETE')
		@csrf
			<button>Delete</button>
		</form>
	</div>
</article>
</x-layout.user>
