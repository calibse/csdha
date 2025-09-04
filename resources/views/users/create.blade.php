<x-layout.user class="signup form" :$backRoute title="Sign up">
	<article class="article">
		<x-alert/>
		<form method="post" action="{{ route('users.store') }}" >
			@csrf
			<p>
				<label for="email">Email</label>
				<input disabled type="email" id="email" name="email" value="{{ $email }}">
			</p>
			<p>
				<label for="first_name">First Name</label>
				<input id="first_name" name="first_name" value="{{ old('first_name') }}">
			</p>
			<p>
				<label for="middle_name">Middle Name (optional)</label>
				<input id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
			</p>
			<p>
				<label for="last_name">Last Name</label>
				<input id="last_name" name="last_name" value="{{ old('last_name') }}">
			</p>
			<p>
				<label for="suffix_name">Suffix Name (optional)</label>
				<input id="suffix_name" name="suffix_name"  value="{{ old('suffix_name') }}">
			</p>
			<p>
				<label for="username">Username</label>
				<input id="username" name="username" value="{{ old('username') }}">
			</p>
			<p>
				<label for="password">Password</label>
				<input type="password" id="password" name="password">
			</p>
			<p>			
				<label for="password_confirmation">Confirm password</label>
				<input type="password" id="password_confirmation" name="password_confirmation">
			</p>
			<p class="form-submit">
				<button type="submit">Sign up</button>
			</p>
		</form>
	</article>
</x-layout>
