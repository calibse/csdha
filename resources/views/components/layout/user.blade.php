@use('Illuminate\Support\Facades\Route')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>
	@switch ($siteContext)
	@case ('user')
		CSDHA
		@break
	@case ('admin')
		CSDHA Admin
		@break
	@endswitch
	</title>
	{{--
	@vite(['resources/scss/app.scss', 'resources/js/app.js']) 
	--}}
	@vite(['resources/scss/app.scss']) 
</head>
<body class="main-body {{ $index ? 'index' : null }} {{ $form ? 'form' : null }}">
@if ($index)
	<header class="main-header" id="menu">
		<a href="#" class="close-menu-button">
			<span class="text">Close Menu</span>
		</a>
		<div class="content-block">
			<div class="main-header-title">
				<div class="main-brand">
					<span class="logo">
						<img src="{{ asset('storage/app-logo.png') }}">
						{{--
						<img src="{{ Vite::asset('resources/images/app-logo.png') }}">
						--}}
					</span>
					<span class="name">CSDHA</span>
				</div>
				<div class="main-account-link">
						<a href="{{ route('profile.edit') }}">
							<img class="icon" src="{{ asset('icon/dark/user-circle-duotone.png') }}">
							<span class="text">Profile</span>
						</a>
				</div>
			</div>
			<div class="main-header-info">
				<div class="account">
					<div class="info">
						<div class="avatar">
						@if (auth()->user()->avatar_filepath)
							<img src="{{ route('profile.showAvatar', ['avatar' => basename(auth()->user()->avatar_filepath)]) }}">
						@else
							<img src="{{ asset('icon/user.png') }}">
						@endif
						</div>
						<div class="details">
							<p>{{ auth()->user()->full_name }}</p>
						@if ($siteContext === 'admin')
							<p>{{ ucwords(auth()->user()->role?->name) }}</p>
						@elseif ($siteContext === 'user')
							<p>{{ auth()->user()->position?->name }}</p>
						@endif
						</div>
					</div>
				@if ($siteContext === 'user')
					<p class="main-action">
						<a href="{{ route('profile.edit') }}">
							<span class="icon"><x-phosphor-pencil-simple/></span>
							<span class="text">Edit profile</span>
						</a>
					</p>
				@endif
				</div>
			</div>
			<nav class="main-header-menu">
				<p class="title">Main Menu</p>
				<ul class="list">
				@if ($siteContext === 'user')
					<li>
						<a href="{{ route('user.home') }}">
							<img class="icon" src="{{ asset('icon/dark/house-duotone.png') }}">
                            <span class="text">Home</span>
						</a>
					</li>
					@can ('viewAny', 'App\Models\Gpoa')
					<li>
						<a href="{{ route('gpoa.index') }}">
							<img class="icon" src="{{ asset('icon/dark/blueprint-duotone.png') }}">
							<span class="text">GPOA</span>
						</a>
					</li>
					@endcan
					@can ('viewAny', 'App\Models\Event')
					<li>
						<a href="{{ route('events.index') }}">
							<img class="icon" src="{{ asset('icon/dark/calendar-duotone.png') }}">
							<span class="text">Events</span>
						</a>
					</li>
					@endcan
					@can ('viewAnyAccomReport', 'App\Models\Event')
					<li>
						<a href="{{ route('accom-reports.index') }}">
							<img class="icon" src="{{ asset('icon/dark/files-duotone.png') }}">
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
					@can ('viewAny', 'App\Models\Student')
					<li>
						<a href="{{ route('students.index') }}">
							<span class="icon"><x-phosphor-student/></span>
							<span class="text">Students</span>
						</a>
					</li>
					@endcan
					--}}
					@can ('viewAny', 'App\Models\Position')
					<li>
						<a href="{{ route('positions.index') }}">
							<img class="icon" src="{{ asset('icon/dark/users-three-duotone.png') }}">
							<span class="text">Central Body</span>
						</a>
					</li>
					@endcan
					@can ('viewAttendance', 'App\Models\Event')
					<li>
						<a href="{{ route('attendance.create') }}">
							<img class="icon" src="{{ asset('icon/dark/user-check-duotone.png') }}">
							<span class="text">Attendance</span>
						</a>
					</li>
					@endcan
					<li>
						<a href="{{ route('user.logout') }}">
							<img class="icon" src="{{ asset('icon/dark/sign-out-duotone.png') }}">
							<span class="text">Sign out</span>
						</a>
					</li>
				@elseif ($siteContext === 'admin')
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
					<li>
						<a href="{{ route('admin.logout') }}">
							<span class="icon"><x-phosphor-sign-out/></span>
							<span class="text">Sign out</span>
						</a>
					</li>
				@endif
				</ul>
			</nav>
		</div>
	</header>
@endif
	<main class="main-main">
		<header class="main-content-header">
			<div class="content-block">
				<div class="nav-actions">
				@if ($index)
					<a href="#menu" class="main-menu-button">
						<img class="icon" src="{{ asset('icon/dark/list-duotone.png') }}">
						<span class="text">Menu</span>
					</a>
				@elseif ($backRoute)
					<a class="main-back-link" href="{{ $backRoute }}" >
						<img class="icon" src="{{ asset('icon/dark/arrow-left-duotone.png') }}">

						<span class="text">Back to previous page</span>
					</a>
				@else
					<a class="main-back-link" href="{{ route($route, $routeParams) }}" >
						<img class="icon" src="{{ asset('icon/dark/arrow-left-duotone.png') }}">
						<span class="text">Back to previous page</span>
					</a>
				@endif
				</div>
				<h1 class="title">{{ $title ?? 'CSDHA' }}</h1>
			</div>
		</header>
		<div {{ $attributes->merge(['class' => 'main-content']) }}>
		@if (isset($toolbar) && $toolbar->hasActualContent())
			<nav class="main-toolbar">{{ $toolbar }}</nav>
		@endif
			<div class="content-block">
				{{ $slot }}
			</div>
		</div>
	</main>
</body>
</html>
