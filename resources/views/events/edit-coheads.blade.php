<x-layout.user content-view class="events form" :$backRoute title="Edit co-heads">
<div class="article">
	<x-alert error-bag="event-coheads_edit" />
	<form method="post" action="{{ $formAction }}">
	@csrf
	@method('PUT')
		<p>
			<label>Co-head (optional)</label>
			<select multiple size="5" name="coheads[]"> 
			@foreach ($selectedCoheads as $selectedCohead)
				<option value="{{ $selectedCohead->public_id }}" selected>{{ $selectedCohead->full_name }}</option>
			@endforeach
			@foreach ($coheads as $cohead)
				<option value="{{ $cohead->public_id }}">{{ $cohead->full_name }}</option>
			@endforeach
			</select>
		</p>
		<p class="form-submit">
			<button>Update</button>
		</p>
</div>
</x-layout>