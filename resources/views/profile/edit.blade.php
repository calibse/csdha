<x-layout.user class="profile form" :$backRoute title="Edit Profile">
	<article class="article">
        <x-alert/>
		<form method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
			@csrf
            @method('PUT')
			<p>
				<label>Avatar</label>
				<input type="file" name="avatar" accept="image/png, image/jpeg">
			</p>
			<p>
				<input id="remove-avatar" type="checkbox" {{ auth()->user()->avatar_filepath ? '' : 'disabled' }} name="remove_avatar" value="1">
				<label for="remove-avatar">Remove avatar</label>
			</p>
			<p>
				<label>Username</label>
				<input name="username" value="{{ old('username') ?? auth()->user()->username }}">
			</p>
			<p>
				<label>Email</label>
                <a href="{{ $emailRoute }}">Change here</a>
			</p>
            <p>
                <label>Password</label>
                <a href="{{ $passwordRoute }}">Change here</a>
            </p>
			<p class="form-submit">
				<button type="submit">Update profile</button>
			</p>
		</form>
	</article>
</x-layout.editing>
