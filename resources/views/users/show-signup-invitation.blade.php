<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CSDHA</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
    <article class="signup-invitation">
        <h1 class="title">You are invited to sign up for a CSDHA account</h1>
        <div class="login-button-container">
            <a href="{{ route('auth.redirect', ['provider' => 'google', 'invite-code' => $inviteCode ], false) }}" class="login-button google">
                <img src="{{Vite::asset('resources/images/google.webp') }}">
                Sign in with Google
            </a>
        </div>
    </article>
</body>