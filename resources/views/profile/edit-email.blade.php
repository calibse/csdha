<x-layout.user form class="profile form" :$backRoute title="Change Email">
    <article class="article">
        <x-alert/>
    @if ($user->email && !$user->email_verified_at)
        <p>
            Your email is not verified. Please check your inbox to verify.
        </p>
        <p>
            <a href="{{ $resendRoute }}">Click to resend</a>
        </p>
    @endif
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
    </article>
</x-layout>
