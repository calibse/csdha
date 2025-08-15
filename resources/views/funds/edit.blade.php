@php
$routeParams = ['fund' => $fund->id];
@endphp
<x-layout.user class="editing" editing route="funds.show" :$routeParams title="Edit Fund">
    <h2 class="title">{{ $fund->event->title }}</h2>
    <form method="POST" action="{{ route('funds.update', ['fund' => $fund->id], false) }}" enctype="multipart/form-data">
	@method("PUT")
	@csrf

	<p>
	    <label>Collected</label>
	    <input required name="collected" type="number" step="0.1" value="{{ $fund->collected }}">
	</p>
	<p>
	    <label>Spent</label>
	    <input required name="spent" type="number" step="0.1" value="{{ $fund->spent }}">
	</p>
	<p>
	    <button type="submit">Update fund</button>
	</p>
    </form>
</x-layout.editing>
