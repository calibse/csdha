<x-layout.user content-view class="accom-reports form" :$backRoute title="Change AR Background">
	<div class="article">
		<x-alert/>
		<form method="post" action="{{ $formAction }}" enctype="multipart/form-data">
		@csrf
		@method('PUT')
			<p>
				<label>Background file</label>
				<input id="background-file" name="background_file" type="file" accept="image/jpeg, image/png, image/webp, image/avif">
			</p>
			<p class="checkbox">
				<input id="remove-background" type="checkbox" name="remove_background" value="1">
				<label for="remove-background">Remove background</label>
			</p>
			<p class="form-submit">
				<button>Update</button>
			</p>
		</form>
	</div>
</x-layout>
