@use('Illuminate\Support\Facades\Route')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="color-scheme" content="only light">
	<meta name="csrf-token" content="{{ csrf_token() }}">
@switch ($siteContext)
@case ('user')
	<title>{{ $title }} - CSDHA</title>
	@break
@case ('admin')
	<title>{{ $title }} - CSDHA Admin</title>
	@break
@endswitch
	<link rel="icon" href="{{ asset('favicon.ico') . '?id=' . cache('website_logo_id') }}" />
{{--
	<script defer src="{{ asset('js/main.js') . '?v=1.0' }}"></script>
--}}
	@vite_legacy('resources/js/app-legacy.js')
	@vite(['resources/scss/app.scss', 'resources/js/app.js']) 
</head>
<body class="main-body {{ $index ? 'index' : null }} {{ $form ? 'form' : null }}">
@if ($index)
	<div class="main-header" id="menu">
		<a href="#" class="close-menu-button">
			<span class="text">Close Menu</span>
		</a>
		<div class="content-block">
			<header> 
				<div class="main-header-title">
					<div class="main-brand">
						<img class="logo" src="{{ asset('storage/website-logo.png') . '?id=' . cache('website_logo_id') }}">

						<span class="name">CSDHA</span>
					</div>
					<div class="main-account-link">
							<a href="{{ route('profile.edit') }}">
								<img class="icon" src="{{ asset('icon/dark/user-circle-duotone.png') }}">
								<span class="text">Account</span>
							</a>
					</div>
				</div>
				<div class="main-header-info">
					<div class="account">
						<div class="info">
							<div class="avatar">
							@if (auth()->user()->avatar_filepath)
								@if ($siteContext === 'user')
								<img src="{{ route('profile.showAvatar', ['avatar' => basename(auth()->user()->avatar_filepath)]) }}">
								@elseif ($siteContext === 'admin')
								<img src="{{ route('admin-profile.showAvatar', ['avatar' => basename(auth()->user()->avatar_filepath)]) }}">
								@endif
							@else
								<img src="{{ asset('icon/user.png') }}">
							@endif
							</div>
							<div class="details">
								<p class="name">{{ auth()->user()->full_name }}</p>
							@if ($siteContext === 'admin')
								<p class="position">{{ ucwords(auth()->user()->role?->name) }}</p>
							@elseif ($siteContext === 'user')
								<p class="position">{{ auth()->user()->position?->name }}</p>
							@endif
							</div>
						</div>
					@if ($siteContext === 'user')
						<p class="main-action">
							<a href="{{ route('profile.edit') }}">
								<img class="icon" src="{{ asset('icon/dark/pencil-simple-duotone.png') }}">
								<span class="text">Edit account</span>
							</a>
						</p>
					@endif
					</div>
				</div>
				<div class="main-header-menu">
					<nav> 
						<p class="title">Menu</p>
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
								<a href="{{ route('settings.index') }}">
									<img class="icon" src="{{ asset('icon/dark/wrench-duotone.png') }}">
									<span class="text">Settings</span>
								</a>
							</li>
							<li>
								<a href="{{ route('user.logout') }}">
									<img class="icon" src="{{ asset('icon/dark/sign-out-duotone.png') }}">
									<span class="text">Sign out</span>
								</a>
							</li>
						@elseif ($siteContext === 'admin')
							<li>
								<a href="{{ route('admin.home') }}">
									<img class="icon" src="{{ asset('icon/dark/house-duotone.png') }}">
									<span class="text">Home</span>
								</a>
							</li>
							<li>
								<a href="{{ route('audit.index') }}">
									<img class="icon" src="{{ asset('icon/dark/table-duotone.png') }}">
									<span class="text">Audit Trail</span>
								</a>
							</li>
							{{--
							<li>
								<a href="{{ route('analytics.index') }}">
									<img class="icon" src="{{ asset('icon/dark/chart-line-up-duotone.png') }}">
									<span class="text">Analytics</span>
								</a>
							</li>
							--}}
							<li>
								<a href="{{ route('accounts.index') }}">
									<img class="icon" src="{{ asset('icon/dark/user-square-duotone.png') }}">
									<span class="text">Accounts</span>
								</a>
							</li>
							<li>
								<a href="{{ route('roles.index') }}">
									<img class="icon" src="{{ asset('icon/dark/user-gear-duotone.png') }}">
									<span class="text">Roles</span>
								</a>
							</li>
							<li>
								<a href="{{ route('admin.logout') }}">
									<img class="icon" src="{{ asset('icon/dark/sign-out-duotone.png') }}">
									<span class="text">Sign out</span>
								</a>
							</li>
						@endif
						</ul>
					</nav>
				</div>
			</header>
		</div>
	</div>
	@endif
	<div class="main-main">
		<main> 
			<div class="main-content-header">
				<div class="content-block">
					<header> 
						<div class="nav-actions">
						@if ($index)
							<a href="#menu" class="main-menu-button">
								<img class="icon" src="{{ asset('icon/light/list-duotone.png') }}">
								<span class="text">Menu</span>
							</a>
						@elseif ($backRoute)
							<a id="main-back-link" class="main-back-link" href="{{ $backRoute }}" >
								<img class="icon" src="{{ asset('icon/light/arrow-left-duotone.png') }}">

								<span class="text">Back to previous page</span>
							</a>
						@else
							<a id="main-back-link" class="main-back-link" href="{{ route($route, $routeParams) }}" >
								<img class="icon" src="{{ asset('icon/light/arrow-left-duotone.png') }}">
								<span class="text">Back to previous page</span>
							</a>
						@endif
						</div>
						<h1 title="{{ $title }}" class="title">{{ $title ?? 'CSDHA' }}</h1>
					</header>
				</div>
			</div>
			<div {{ $attributes->merge(['class' => 'main-content']) }}>
			@if (isset($toolbar) && $toolbar->hasActualContent())
				<div class="main-toolbar">
					<nav> 
						{{ $toolbar }}
					</nav>
				</div>
			@endif
				<div class="content-block">
					{{ $slot }}
				</div>
			</div>
		</main>
	</div>
</body>
</html>
