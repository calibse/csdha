<x-layout.text-only>
    <article class="signup-invitation">
        <h1 class="title">You are invited to sign up for a CSDHA account</h1>
        <x-alert/>
        <div class="login-button-container">
            <p>
                <a class="signin-link" href="{{ $emailRoute }}">
			<img class="icon" src="{{ asset('icon/light/envelope-simple-duotone.png') }}">
			<span class="text">Sign up with email</span>
                </a>
            </p>
            <p>
                <a class="signin-link" href="{{ $googleRoute }}">
			<img class="icon" src="{{ asset('icon/light/google-logo-duotone.png') }}">
			<span class="text">Sign up with Google</span>
                </a>
            </p>
        </div>
    </article>
</x-layout.text-only>
