@php
	$routeParams = ['id' => $position->id];
@endphp

<x-layout.user class="editing" editing route="positions.show" :$routeParams title="Delete Officer Position">
<div>
	<div class="confirm-delete">
		<p>Are you sure you want to delete the <strong>{{ $position->name }}</strong> position?</p>
		<div class="buttons">
			<div>
				<a class="button" href="{{ route('positions.delete', ['id' => $position->id], false) }}">Yes</a>
			</div>
			<div>
				<a class="button" href="{{ route('positions.show', ['id' => $position->id], false) }}">No</a>
			</div>
		</div>
	</div>
</div>
</x-layout.editing>
