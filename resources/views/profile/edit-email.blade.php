<x-layout.user form class="profile form" :$backRoute title="Change Email">
<div class="article">
	<x-alert error-bag="profile-email_edit"/>
	<form method="post" action="{{ $formAction }}">
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
			<button>Update</button>
		</p>
	</form>
</div>
</x-layout>