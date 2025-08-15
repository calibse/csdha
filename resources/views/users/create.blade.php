<x-layout.user class="signup" route="login.login" title="Sign up">
	@if ($errors->any())
	<aside class="validation-errors">
		<p class="title"><strong>There is a problem</strong></p>
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</aside>
	@endif

	<form method="POST" 
		action="{{ route('users.create', [], false) }}"
	>
		@csrf
		<p>
			<label for="first_name">First Name</label>
			<input type="text" 
				maxlength="50" 
				id="first_name" 
				name="first_name"
			>
		</p>
		<p>
			<label for="middle_name">Middle Name</label>
			<input type="text" 
				maxlength="50" 
				id="middle_name" 
				name="middle_name"
			>
		</p>
		<p>
			<label for="last_name">Last Name</label>
			<input type="text" 
				maxlength="50" 
				id="last_name" 
				name="last_name"
			>
		</p>
		<p>
			<label for="suffix_name">Suffix Name</label>
			<input type="text" 
				maxlength="10" 
				id="suffix_name" 
				name="suffix_name"
			>
		</p>
		<p>
			<label for="email">Email</label>
			<input type="email" 
				minlength="5" 
				maxlength="255" 
				id="email" 
				name="email"
			>
		</p>
		<p>
			<label for="username">Username (required)</label>
			<input type="text" 
				required 
				minlength="5" 
				maxlength="50" 
				id="username" 
				name="username"
			>
		</p>
		<p>
			<label for="password">Password (required)</label>
			<input type="password" 
				required
				minlength="8" 
				maxlength="55" 
				id="password" 
				name="password"
			>
		</p>
		<p>			
			<label for="password_confirmation">Confirm password (required)</label>
			<input type="password" 
				required 
				minlength="8" 
				maxlength="55" 
				id="password_confirmation" 
				name="password_confirmation"
			>
		</p>
		<p>
			<button type="submit">Sign up</button>
		</p>
	</form>
</x-layout>
