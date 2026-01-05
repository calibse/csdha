<x-layout.user form class="settings form" :$backRoute title="Update Logos">
<div class="article">
	<x-alert/>
	<form method="post" action="{{ $formAction }}" enctype="multipart/form-data">
	@csrf
	@method('PUT')
		<p>
			<label for="website">Website Logo</label>
			<input type="file" name="website" accept="image/png, image/webp, image/avif">
		</p>
		<p>
			<label for="organization">Organization Logo</label>
			<input type="file" name="organization" accept="image/png, image/webp, image/avif">
		</p>
		<p>
			<label for="university">University Logo</label>
			<input type="file" name="university" accept="image/png, image/webp, image/avif">
		</p>
		<p class="form-submit">
			<button>Update</button>
		</p>
	</form>
</div>
</x-layout.editing>
