@php
$routeParams = ['platform' => $platform->id];
@endphp
<x-layout.user class="editing" editing route="platforms.show" :$routeParams title="Edit Platform">
    <h2>{{ $platform->name }}</h2>
    <form method="POST" action="{{ route('platforms.update', ['platform' => $platform->id], false) }}" enctype="multipart/form-data">
	@method("PUT")
	@csrf

	<p>
	    <label>Name</label>
	    <input required name="name" type="text" value="{{ $platform->name }}">
	</p>
	<p>
	    <label>Description</label>
	    <textarea required name="description">{{ $platform->description }}</textarea>
	</p>
	<p>
	    <label>Start date</label>
	    <input required name="start_date" type="date" value="{{ $platform->start_date }}">
	</p>
	<p>
	    <label>End date</label>
	    <input required name="end_date" type="date" value="{{ $platform->end_date }}">
	</p>
	<p>
	    <label>Progress</label>
	    <input required min="0" max="100" name="progress" type="number" step="0.1" value="{{ $platform->progress }}">
	</p>
	<p>
	    <button type="submit">Update Platform</button>
	</p>
    </form>
</x-layout.editing>
