<x-layout.text-only>
    <article class="signup-invitation">
        <h1 class="title">You are invited to sign up for a CSDHA account</h1>
        <x-alert/>
        <div class="login-button-container">
            <p>
                <button form="form-email" class="login-button">
                    <x-phosphor-envelope-simple/>
                    Sign up with email
                </button>
            </p>
            <p>
                <button form="form-google" class="login-button">
                    <img src="{{ Vite::asset('resources/images/google.webp') }}">
                    Sign up with Google
                </button>
            </p>
        </div>
        <form id="form-email" action="{{ $emailRoute }}">
            <input hidden name="invite_code" value="{{ $inviteCode }}">
        </form>
        <form id="form-google" action="{{ $googleRoute }}">
            <input hidden name="invite_code" value="{{ $inviteCode }}">
        </form>
    </article>
</x-layout.text-only>
