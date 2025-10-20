<x-layout.user form class="profile form" :$backRoute title="Edit Account">
	<div class="article">
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
				<label>Google Account</label>
			@if (auth()->user()->google && !auth()->user()->email_verified_at)
				Connected (Add an email to be able to remove this account)
			@elseif (auth()->user()->google)
				<a href="{{ $googleRoute }}">Remove</a>
			@else
				<a href="{{ $googleRoute }}">Connect</a>
			@endif
			</p>
			<p>
				<label>Email</label>
			@if ($hasPassword)
				<a href="{{ $emailRoute }}">Change here</a>
			@else
				Set up a password first.
			@endif
			</p>
			<p>
				<label>Password</label>
				<a href="{{ $passwordRoute }}">
			@if ($hasPassword)
				Change here
			@else
				Set up here
			@endif
				</a>
			</p>
			<p>
				<label>Username</label>
				<input name="username" value="{{ old('username') ?? auth()->user()->username }}">
			</p>
			<p class="form-submit">
				<button type="submit">Update profile</button>
			</p>
		</form>
	</div>
</x-layout.editing>
