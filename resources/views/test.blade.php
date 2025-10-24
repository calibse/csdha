<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CSDHA</title>
        <script defer src="{{ asset('js/main.js') . '?v=1.0' }}"></script>
	@vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
	<p>{{ now(config('timezone')) }}</p>
	<p class="textbox"><a href="">Box</a> Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello Hello  Hello Hello Hello Hello Hello Hello</p>
	<a id="box" class="box">
		<span class="divo"><img></span>
		<span class="diva"></span>
	</a>
</body>
</html>
