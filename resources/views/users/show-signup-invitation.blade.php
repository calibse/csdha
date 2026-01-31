<x-layout.text-only>
    <div class="signup-invitation">
        <h1 class="title">You are invited to sign up for a CSDHA account</h1>
        <x-alert/>
        <div class="login-button-container">
            <p>
                <a class="signin-link" href="{{ $emailRoute }}">
			<img class="icon" src="{{ asset('icon/light/envelope-simple.svg') }}">
			<span class="text">Sign up with email</span>
                </a>
            </p>
            <p>
                <a class="signin-link" href="{{ $googleRoute }}">
			<img class="icon" src="{{ asset('icon/light/google-logo.svg') }}">
			<span class="text">Sign up with Google</span>
                </a>
            </p>
        </div>
    </div>
</x-layout.text-only>
