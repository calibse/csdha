<x-layout.user form title="Create Sign-up Invite" :$backRoute class="accounts signup-invitation form">
<article class="article">
	<x-alert/>
	<form type="post" action="{{ $formAction }}">
		<p>
			<label>Council Body Position</label>
			<select name="position">
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
			<input type="email" name="email" value="{{ old('email') }}">
		</p>
		<p class="form-submit">
			<button >Send</button>
		</p>
	</form>
</article>
</x-layout.user>
