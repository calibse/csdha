<x-layout.user form class="profile form" :$backRoute title="Edit Account">
<div class="article">
	<x-alert/>
@if ($user->email && !$user->email_verified_at)
	<p>
		Your email is not verified. Please check your inbox to verify.
	</p>
	<p>
		<a href="{{ $resendRoute }}">Click to resend</a>
	</p>
@endif
	<form method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
	@csrf
	@method('PUT')
		<p>
			<label>Avatar</label>
			<input id="file-input" type="file" name="avatar" accept="image/png, image/jpeg">
		</p>
		<p>
			<input id="remove-avatar" type="checkbox" {{ auth()->user()->avatar_filepath ? '' : 'disabled' }} name="remove_avatar" value="1">
			<label for="remove-avatar">Remove avatar</label>
		</p>
		<p>
			<label>Google Account</label>
		@if (auth()->user()->google && !$emailVerified)
			Connected
		@elseif (auth()->user()->google)
			<a href="{{ $googleRoute }}">Remove</a>
		@else
			<a href="{{ $googleRoute }}">Connect</a>
		@endif
		</p>
		<p>
			<label>Email 
			@can ('updateEmail', 'App\Models\User')
				<span>[ <a id="profile-email_edit-button" href="{{ $emailRoute }}">Edit</a> ]</span>
			@endcan
			</label>
		@can ('updateEmail', 'App\Models\User')
			{{ ($hasEmail ? $email . ' ' . (!$emailVerified ? '(Unverified)' : null) : 'No email') }}
		@else
			Set up a password first.
		@endif
		</p>
		<p>
			<label>Password</label>
		@can ('updatePassword', 'App\Models\User')
			<a href="{{ $passwordRoute }}">
			@if ($hasPassword)
			Change here
			@else
			Set up here
			@endif
			</a>
		@else
			Set up an email first.
		@endcan
		</p>
		<p>
			<label>Username</label>
			<input autocomplete="off" name="username" value="{{ old('username') ?? auth()->user()->username }}">
		</p>
		<p class="form-submit">
			<button type="submit">Update profile</button>
		</p>
	</form>
</div>
<x-window class="form" id="profile-email_edit" title="Edit email">
	<form method="post" action="{{ $editEmailAction }}">
	@csrf
	@method('PUT')
		<p>
			<label for="email">Email</label>
			<input id="email" name="email" value="{{ old('email') ?? $user->email }}">
		</p>
		<p>
			<label for="password">Password</label>
			<input type="password" id="password" name="password">
		</p>
		<p class="form-submit">
			<button type="button" id="profile-email_edit_close">Cancel</button>
			<button>Update</button>
		</p>
	</form>
</x-window>
</x-layout.editing>
