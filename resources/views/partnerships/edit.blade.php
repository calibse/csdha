@php
$routeParams = ['partnership' => $partnership->id];
@endphp
<x-layout.user class="editing" editing route="partnerships.show" :$routeParams title="Edit Partnership">
    <form method="POST" action="{{ route('partnerships.update', ['partnership' => $partnership->id], false) }}" enctype="multipart/form-data">
	@method ('PUT')
	@csrf

	<p>
	    <label for="org-name">Organization name</label>
	    <input required name="org_name" id="org-name" type="text" value="{{ $partnership->organization_name }}">
	</p>
	<p>
	    <label>Purpose</label>
	    <textarea required name="purpose">{{ $partnership->purpose }}</textarea>
	</p>
	<p>
	    <label>Benefits</label>
	    <textarea required name="benefits">{{ $partnership->benefits }}</textarea>
	</p>
	<p>
	    <label>Action</label>
	    <textarea required name="action">{{ $partnership->action }}</textarea>
	</p>
	<p>
	    <label>Links</label>
	    <textarea required name="links">{{ $partnership->links }}</textarea>
	</p>
	<p>
	    <label>Accomplished by</label>
	    <textarea required name="accomplished_by">{{ $partnership->accomplished_by }}</textarea>
	</p>
	<p>
	    <label>Officer</label>
	    <input required name="officer" type="text" value="{{ $partnership->officer }}">
	</p>
	<p>
	    <button type="submit">Save Partnership</button>
	</p>
    </form>
</x-layout.editing>
