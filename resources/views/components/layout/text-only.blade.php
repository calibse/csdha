<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="color-scheme" content="only light">
	<title>CSDHA</title>
	<link rel="icon" href="{{ asset('favicon.ico') . '?id=' . cache('website_logo_id') }}" />
	@vite(['resources/scss/app.scss'])
	<style>
		body {
			margin: 0 1em;
		}
	</style>
</head>
<body>
	{{ $slot }}
</body>
</html>
