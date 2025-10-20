<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CSDHA {{ $type === 'admin' ? 'Admin' : null }}</title>
	@vite(['resources/scss/app.scss'])
</head>
<body class="front-body">
	<div class="front-main">
		<div class="content-block">
			<div class="front-header">
				<header>
					<h1 class="title">
							<img class="logo"src="{{ asset('storage/website-logo.png') }}">

							<span class="name">
								<span class="org-name">COMPUTER SOCIETY</span>
								<span class="app-name">DIGITAL HUB AND ARCHIVES</span>
							</span>
					</h1>
				</header>
			</div>
			<main>
				<div class="front-content">
					<article> 
					@if (auth()->check())
						<h2 class="title">Welcome back!</h2>
						<form action="{{ $homeRoute }}">
							<button class="login-button">Proceed with My Account</button>
						</form>
					@else
						<h2 class="title">Sign in to CSDHA {{ $type === 'admin' ? '(for Admin)' : null }}</h2>
						<x-alert/>
						<div class="sign-in-with">
							<article> 
								<p>
									<a href="{{ $googleSigninRoute }}" class="signin-link">
										<img class="icon" src="{{ asset('icon/dark/google-logo-duotone.png') }}">
										<span class="text">Sign in with Google</span>
									</a>
								</p>
							</article>
						</div>
						<div class="sign-in-separator">
							<p class="text">OR</p>
						</div>
						<div class="sign-in">
							<article> 
								<form method="post" action="{{ $signinRoute }}" >
									@csrf
									<p>
										<label for="username">Username or Email</label>
										<input id="username" name="username">
									</p>
									<p>
										<label for="password">Password</label>
										<input type="password" id="password" name="password">
									</p>
									<p class="button"><button type="submit">Sign in</button></p>
								</form>
							</article>
						</div>
						<p>
							<a href="{{ $passwordResetRoute }}">Reset password</a>
						</p>
					@endif
					</article>
				</div>
			</main>
			<div class="front-footer">
				<footer> 
				</footer>
			</div>
			<div class="decor"></div>
		</div>
	</div>
</body>
</html>
