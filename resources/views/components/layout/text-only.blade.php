<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CSDHA</title>
	<link rel="icon" href="/favicon.ico?id={{ cache('website_logo_id') }}" />
    @vite(['resources/scss/app.scss'])
</head>
<body>
    {{ $slot }}
</body>
</html>
