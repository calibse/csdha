<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>DHA</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <main>
            <h1>Digital Hub and Archives</h1>
            <a class="button is-primary" href="{{ route('users.create') }}">Register</a>
            <a class="button is-info" href="{{ route('login.login') }}">Login</a>
        </main>
    </body>
</html>
