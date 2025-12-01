<x-layout.user content-view :$backRoute class="events form" title="Delete event link">
<div class="article">
	<p>
		Are you sure you want to delete event link "<strong>{{ $link->name }}</strong>"?
	</p> 
	<div class="submit-buttons">
		<button form="cancel-form">Cancel</button>
		<button form="delete-form">Delete</button>
	</div>
	<form id="cancel-form" action="{{ $backRoute }}"></form>
	<form id="delete-form" method="post" action="{{ $formAction }}"> 
	@method('DELETE') 
	@csrf 
	</form>
</div>
</x-layout.user>
