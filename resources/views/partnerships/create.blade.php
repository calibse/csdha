<x-layout.user class="editing" editing route="partnerships.index" title="Add Partnership">
    <form method="POST" action="{{ route('partnerships.store', [], false) }}" enctype="multipart/form-data">
	@csrf

	<p>
	    <label for="org-name">Organization name</label>
	    <input required name="org_name" id="org-name" type="text">
	</p>
	<p>
	    <label>Purpose</label>
	    <textarea required name="purpose"></textarea>
	</p>
	<p>
	    <label>Benefits</label>
	    <textarea required name="benefits"></textarea>
	</p>
	<p>
	    <label>Action</label>
	    <textarea required name="action"></textarea>
	</p>
	<p>
	    <label>Links</label>
	    <textarea required name="links"></textarea>
	</p>
	<p>
	    <label>Accomplished by</label>
	    <textarea required name="accomplished_by"></textarea>
	</p>
	<p>
	    <label>Officer</label>
	    <input required name="officer" type="text">
	</p>
	<p>
	    <button type="submit">Save Partnership</button>
	</p>
    </form>
</x-layout.editing>
