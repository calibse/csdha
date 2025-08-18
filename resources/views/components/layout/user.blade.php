@use('Illuminate\Support\Facades\Route')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ isset($siteContext) ? 'CSDHA Admin' : 'CSDHA' }}</title>
	@vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="main-body {{ $index ? 'index' : null }}">
	@if ($index)
	<header class="main-header" id="menu">
		<a href="#" class="close-menu-button">
			<span class="text">Close Menu</span>
		</a>
		<div class="content-block">
			<div class="main-info">
				<a class="brand" href="#">
					<span class="logo">
						<img src="{{ Vite::asset('resources/images/app-logo.png') }}">
					</span>
					<span class="name">CSDHA</span>
				</a>
				<div class="account">
					<div class="info">
						<div class="avatar">
							@if (auth()->user()->avatar_filepath)
							<img src="{{ route('profile.showAvatar', ['avatar' => basename(auth()->user()->avatar_filepath)]) }}">
							@else
							<x-icon.avatar/>
							@endif
						</div>
						<dl class="details">
							<dt class="name">Name</dt><dd class="value">{{ auth()->user()->full_name }}</dd>
							<dt class="name">Role</dt>
							@if (isset($siteContext))
								<dd class="value">{{ ucwords(auth()->user()->role?->name) }}</dd>
							@else
								<dd class="value">{{ auth()->user()->position?->name }}</dd>
							@endif
						</dl>
					</div>
					<nav class="main-actions">
						<a href="{{ route('profile.edit') }}">
							<span class="icon"><x-phosphor-pencil-simple/></span>
							<span class="text">Edit profile</span>
						</a>
					</nav>
				</div>
			</div>
			<nav class="main-menu">
				<p class="title">Main Menu</p>
				<ul class="list">
					@if (!isset($siteContext))
					<li>
						<a href="{{ route('user.home') }}">
							<span class="icon"><x-phosphor-house/></span>
							<span class="text">Home</span>
						</a>
					</li>
					<li>
						<a href="{{ route('gpoa.index') }}">
							<span class="icon"><x-phosphor-blueprint/></span>
							<span class="text">GPOA</span>
						</a>
					</li>
						@can ('viewAny', 'App\Models\Event')
					<li>
						<a href="{{ route('events.index') }}">
							<span class="icon"><x-phosphor-calendar/></span>
							<span class="text">Events</span>
						</a>
					</li>
					@endcan
					@can ('viewAnyAccomReport', 'App\Models\Event')
					<li>
						<a href="{{ route('accom-reports.index') }}">
							<span class="icon"><x-phosphor-files/></span>
							<span class="text">Accom. Reports</span>
						</a>
					</li>
					@endcan
					{{-- 
					@can ('viewAny', 'App\Models\Meeting')
					<li>
						<a href="{{ route('meetings.index') }}">
							<span class="icon"><x-phosphor-video-conference/></span>
							<span class="text">Meetings</span>
						</a>
					</li>
					@endcan
					@can ('viewAny', 'App\Models\Fund')
					<li>
						<a href="{{ route('funds.index') }}">
							<span class="icon"><x-phosphor-coins/></span>
							<span class="text">Funds</span>
						</a>
					</li>
					@endcan

					@can ('viewAny', 'App\Models\Platform')
					<li>
						<a href="{{ route('platforms.index') }}">
							<span class="icon"><x-phosphor-cube-transparent/></span>
							<span class="text">Platforms</span>
						</a>
					</li>
					@endcan
					@can ('viewAny', 'App\Models\Partnership')
					<li>
						<a href="{{ route('partnerships.index') }}">
							<span class="icon"><x-phosphor-handshake/></span>
							<span class="text">Partnership</span>
						</a>
					</li>
					@endcan
					--}}
						@can ('viewAny', 'App\Models\Student')
					<li>
						<a href="{{ route('students.index') }}">
							<span class="icon"><x-phosphor-student/></span>
							<span class="text">Students</span>
						</a>
					</li>
						@endcan
						@can ('viewAny', 'App\Models\Position')
					<li>
						<a href="{{ route('positions.index') }}">
							<span class="icon"><x-phosphor-users-three/></span>
							<span class="text">Central Body</span>
						</a>
					</li>
						@endcan
					<li>
						<a href="{{ route('attendance.create') }}">
							<span class="icon"><x-phosphor-user-check/></span>
							<span class="text">Attendance</span>
						</a>
					</li>

					{{-- Start of temp routes from admin --}}
					{{--
					@if (Route::has('analytics.index'))
					<li>
						<a href="{{ route('analytics.index') }}">
							<span class="icon"><x-phosphor-chart-line-up/></span>
							<span class="text">Analytics</span>
						</a>
					</li>
					@endif
					@if (Route::has('accounts.index'))
					<li>
						<a href="{{ route('accounts.index') }}">
							<span class="icon"><x-phosphor-user-square/></span>
							<span class="text">Accounts</span>
						</a>
					</li>
					@endif
					@if (Route::has('roles.index'))
					<li>
						<a href="{{ route('roles.index') }}">
							<span class="icon"><x-phosphor-user-gear/></span>
							<span class="text">Roles</span>
						</a>
					</li>
					@endif
					@if (Route::has('audit.index'))
					<li>
						<a href="{{ route('audit.index') }}">
							<span class="icon"><x-phosphor-table/></span>
							<span class="text">Audit Trail</span>
						</a>
					</li>
					@endif
					--}}
					{{-- End of temp routes from admin --}}

					@else
					<li>
						<a href="{{ route('admin.home') }}">
							<span class="icon"><x-phosphor-house/></span>
							<span class="text">Home</span>
						</a>
					</li>
					<li>
						<a href="{{ route('audit.index') }}">
							<span class="icon"><x-phosphor-table/></span>
							<span class="text">Audit Trail</span>
						</a>
					</li>
					{{--
					<li>
						<a href="{{ route('analytics.index') }}">
							<span class="icon"><x-phosphor-chart-line-up/></span>
							<span class="text">Analytics</span>
						</a>
					</li>
					--}}
					<li>
						<a href="{{ route('accounts.index') }}">
							<span class="icon"><x-phosphor-user-square/></span>
							<span class="text">Accounts</span>
						</a>
					</li>
					<li>
						<a href="{{ route('roles.index') }}">
							<span class="icon"><x-phosphor-user-gear/></span>
							<span class="text">Roles</span>
						</a>
					</li>
					@endif
					<li>
						<a href="{{ route('user.logout') }}">
							<span class="icon"><x-phosphor-sign-out/></span>
							<span class="text">Sign out</span>
						</a>
					</li>
				</ul>
			</nav>
		</div>
	</header>
	@endif
	<main class="main">
		<header class="main-content-header">
			<div class="content-block">
				<nav class="nav-actions">
					@if ($index)
					<a href="#menu" class="menu-button">
						<span class="icon"><x-phosphor-list/></span>
						<span class="text">Menu</span>
					</a>
					<template id="menu-button-template">
						<button class="menu-button">
							<span class="icon"><x-phosphor-list/></span>
						</button>
					</template>
					@else
						@if ($backRoute)
					<a class="back-link" href="{{ $backRoute }}" >
						<span class="icon"><x-phosphor-arrow-left/></span>
						<span class="text">Back to previous page</span>
					</a>
						@else
					<a class="back-link" href="{{ route($route, $routeParams) }}" >
						<span class="icon"><x-phosphor-arrow-left/></span>
						<span class="text">Back to previous page</span>
					</a>
						@endif
					@endif
				</nav>
				<h1 class="title">{{ $title ?? 'CSDHA' }}</h1>
			</div>
		</header>
		<div {{ $attributes->merge(['class' => 'main-content']) }}>
			@if (isset($toolbar) && $toolbar->hasActualContent())
			<nav class="main-actions toolbar">{{ $toolbar }}</nav>
			@endif
			<div class="content-block">
				{{ $slot }}
			</div>
		</div>
	</main>
</body>
</html>
