<x-layout.user class="editing" editing route="platforms.index" title="Add Platform">
    <form method="POST" action="{{ route('platforms.store', [], false) }}" enctype="multipart/form-data">
	@csrf

	<p>
	    <label for="name">Name</label>
	    <input required name="name" id="name" type="text">
	</p>
	<p>
	    <label for="description">Description</label>
	    <textarea name="description" id="description"></textarea>
	</p>
	<p>
	    <label for="start-date">Start Date</label>
	    <input name="start_date" id="start-date" type="date">
	</p>
	<p>
	    <label for="end-date">End Date</label>
	    <input name="end_date" id="end-date" type="date">
	</p>
	<p>
	    <label for="progress">Progress</label>
	    <input max="100" min="0" name="progress" id="progress" step="0.1" type="number">
	</p>
	<p>
	    <button type="submit">Save Platform</button>
	</p>
    </form>
</x-layout.editing>
