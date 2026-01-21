<x-layout.user form title="Create Sign-up Invite" :$backRoute class="accounts signup-invitation form">
<article class="article">
	<x-alert error-bag="signup-invite_create" />
	<form method="post" action="{{ $formAction }}">
	@csrf
		<p>
			<label>Council Body Position</label>
			<select required name="position">
				<option value="">-- Select position --</option>
				<option value="0" {{ old('position') === '0' ? 'selected' : null }}>
					No position
				</option>
			@foreach ($positions as $position)
				<option value="{{ $position->id }}" {{ old('position') === (string) $position->id ? 'selected' : null }}>
					{{ $position->name }}
				</option>
			@endforeach
			</select>
		</p>
		<p>
			<label>Email address</label>
			<input required type="email" name="email" value="{{ old('email') }}">
		</p>
		<p class="form-submit">
			<button>Send</button>
		</p>
	</form>
</article>
</x-layout.user>
