<x-layout.user content-view :$backRoute class="form gpoa" title="Delete GPOA Activity">
<div class="article">
	<p>
		Are you sure you want to delete GPOA activity "<strong>{{ $activity->name }}</strong>"?
	</p> 
	<div class="submit-buttons">
		<form action="{{ $backRoute }}">
			<button>Cancel</button>
		</form>
		<form method="post" action="{{ $formAction }}">
		@csrf
		@method('DELETE')
			<button>Delete</button>
		</form>
	</div>
</div>
</x-layout.user>
