<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CSDHA</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="editing">
	<header class="editing">
		<p class="title"><a href="{{ route('user.home', [], false) }}"><span>CSDHA</span></a></p>
	</header>
	<nav class="back-link">
		<div>
			<a href="{{ route($backRoute, $routeParams, false) }}"><span class="icon"><x-icon.chevron-left/></span> Back to {{ $backName }}</a>
		</div>
	</nav>
	<main {{ $attributes->merge(['class' => 'app']) }}>
		{{ $slot }}
	</main>
</body>
</html>
