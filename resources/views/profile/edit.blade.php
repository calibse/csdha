<x-layout.user editing class="profile form" route="user.home" title="Edit Profile">
	<article class="article">
		<form method="POST" action="{{ route('profile.update', [], false) }}" enctype="multipart/form-data">
			@csrf
			<p>
				<label>Avatar</label>
				<input type="file" name="avatar" accept="image/png, image/jpeg">
			</p>
			<p>
				<input type="checkbox" {{ auth()->user()->avatar_filepath ? '' : 'disabled' }} name="remove_avatar">
				<label>Remove avatar</label>
			</p>
			<p>
				<label>First Name</label>
				<input type="text" maxlength="50" name="first_name" value="{{ auth()->user()->first_name }}">
			</p>
			<p>
				<label>Middle Name</label>
				<input type="text" maxlength="50" name="middle_name" value="{{ auth()->user()->middle_name }}">
			</p>
			<p>
				<label>Last Name</label>
				<input type="text" maxlength="50" name="last_name" value="{{ auth()->user()->last_name }}">
			</p>
			<p>
				<label>Suffix Name</label>
				<input type="text" maxlength="10" name="suffix_name" value="{{ auth()->user()->suffix_name }}">
			</p>
			{{--
			<p>
				<label>Email</label>
				<input type="text" maxlength="255" name="email" value="{{ auth()->user()->email }}">
			</p>
			--}}
			<p class="form-submit">
				<button type="submit">Update profile</button>
			</p>
		</form>
	</article>
</x-layout.editing>
