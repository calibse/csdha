<x-layout.user form class="profile form" :$backRoute title="Change Password">
<div class="article">
	<x-alert error-bag="profile-password_edit"/>
	<form method="post" action="{{ $formAction }}">
	@csrf
	@method('PUT')
	@if ($hasPassword)
		<p>
			<label for="old_password">Old Password</label>
			<input 
			@if (auth()->user()->password)
				required
			@endif
				maxlength="55" type="password" id="old_password" name="old_password">
		</p>
	@endif
		<p>
			<label for="password">New Password</label>
			<input required maxlength="55" minlength="8" type="password" id="password" name="password">
		</p>
		<p>
			<label for="password_confirmation">Confirm password</label>
			<input required maxlength="55" minlength="8" type="password" id="password_confirmation" name="password_confirmation">
		</p>
		<p class="form-submit">
			<button>Update</button>
		</p>
	</form>
</div>
</x-layout>