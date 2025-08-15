<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CSDHA</title>
	@vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="front-body-wrapper">
	<div class="front-body">
		<header class="front-header">
			<h1 class="title">
				<a class="app-link">
					<span class="logo">
						<img src="{{ 
							Vite::asset('resources/images/app-logo.png') }}">
					</span>
					<span class="name">
						<span class="org-name">COMPUTER SOCIETY</span>
						<span class="app-name">DIGITAL HUB AND ARCHIVES</span>
					</span>
				</a>
			</h1>
		</header>
		<main>
			<article class="front-content">
			@if (auth()->check())
				<h2 class="title">Welcome back!</h2>
				<form action="{{ route('user.home') }}">
					<button class="login-button">
						Proceed with My Account
					</button>
				</form>
			@else
				<h2 class="title">Sign in to CSDHA (for Admin)</h2>
				<article class="sign-in-with">
					<form action="{{ route('auth.redirect', ['provider' => 'google', ]) }}">
						<button class="login-button google">
							<img src="{{ 
								Vite::asset('resources/images/google.webp') }}">
							Sign in with Google
						</button>
					</form>
				</article>
				<div class="sign-in-separator">
					<div class="line"></div>
					<p class="text">OR</p>
					<div class="line"></div>
				</div>
				<article class="sign-in">
					@if ($errors->any())
					<aside class="validation-errors">
						<p class="title"><strong>There is a problem</strong></p>
						<ul>
							@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
							@endforeach
						</ul>
					</aside>
					@endif

					<form method="POST" 
						action="{{ route('admin.auth', [], false) }}"
					>
						@csrf
						<p>
							<label for="username">Username</label>
							<input type="text" 
								id="username"
								required 
								maxlength="50" 
								name="username"
							>
						</p>
						<p>
							<label for="password">Password</label>
							<input type="password" 
								id="password"
								required 
								maxlength="55" 
								name="password"
							>
						</p>
						<p class="button"><button type="submit">Sign in</button></p>
					</form>
				</article>
			@endif
			</article>
		</main>
		<footer class="front-footer">
		</footer>
		<div class="decor"></div>
	</div>
	<div class="front-image"></div>
</body>
</html>

	