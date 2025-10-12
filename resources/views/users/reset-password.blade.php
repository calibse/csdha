<x-layout.user class="password-reset form" :$backRoute title="Reset Password">
	<div class="article">
		<x-alert/>
		<form method="post" action="{{ $formAction }}">
		@csrf
		@method('PUT')
                        <input hidden name="email" value="{{ $email }}">
                        <input hidden name="token" value="{{ $token }}">
			<p>
				<label for="password">New password</label>
				<input type="password" name="password" id="password">
			</p>
			<p>
				<label for="confirm-password">Repeat new password</label>
				<input type="password" name="password_confirmation" id="confirm-password">
			</p>
			<p class="form-submit">
				<button>Change password</button>
			</p>
		</form>
	</div>
</x-layout>
