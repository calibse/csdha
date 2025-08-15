<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CSDHA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="main-header visible">
        <button class="close-menu-button"><x-icon.cross/></button>
        <p class="logo"><a
            href="#"
            >Digital Hub and Archives
        </a></p>
        <nav>
            <p class="title">Main Menu</p>
            <ul>
                <li><a 
                    href="{{ route('user.home', [], false) }}"
                    ><span class="icon">
                        <x-icon.home/>
                    </span>
                    Home
                </a></li>

                @if (auth()->user()->hasPerm('events.view'))
                <li><a 
                    href="{{ route('events.index', [], false) }}"
                    ><span class="icon">
                        <x-icon.calendar/>
                    </span>
                    Events
                </a></li>
                @endif

                @if (auth()->user()->hasPerm('meetings.view'))
                <li><a 
                    href="{{ route('meetings.index', [], false) }}"
                    ><span class="icon">
                        <x-icon.group/>
                    </span>
                    Meetings
                </a></li>
                @endif

                @if (auth()->user()->hasPerm('funds.view'))
                <li><a 
                    href="{{ route('funds.index', [], false) }}"
                    ><span class="icon">
                        <x-icon.coin/>
                    </span>
                    Funds
                </a></li>
                @endif

                @if (auth()->user()->hasPerm('platforms.view'))
                <li><a 
                    href="{{ route('platforms.index', [], false) }}"
                    ><span class="icon">
                        <x-icon.grid/>
                    </span>
                    Platforms
                </a></li>
                @endif

                @if (auth()->user()->hasPerm('partnerships.view'))
                <li><a 
                    href="{{ route('partnerships.index', [], false) }}"
                    ><span class="icon">
                        <x-icon.shake-hands/>
                    </span>
                    Partnership
                </a></li>
                @endif

                <li><a 
                    class="disabled" 
                    href="#"
                    ><span class="icon">
                        <x-icon.grad/>
                    </span>
                    List of Students
                </a></li>

                @if (auth()->user()->hasPerm('council-body.view'))
                <li><a 
                    href="{{ route('positions.index', [], false) }}"
                    ><span class="icon">
                        <x-icon.team/>
                    </span>
                    Council Body
                </a></li>
                @endif

                <li><a 
                    href="{{ route('login.logout', [], false) }}"
                    ><span class="icon">
                        <x-icon.logout/>
                    </span>
                    Sign out
                </a></li>
            </ul>
        </nav>
    </header>
    <main>
        <header class="main-content-header">
            <nav>
                @if ($index)
                <button class="menu-button">
                    <span class="icon">
                        <x-icon.menu/>
                    </span>
                </button>
                @else
                <p><a class="back-link"
                    href="#"
                    >
                    <x-icon.arrow-left/>
                </a></p>
                @endif
            </nav>
            <h1 class="title">Content Title</h1>
        </header>
        <article {{ $attributes->merge(['class' => 'main-content']) }}>
            {{ $slot }}
        </article>
    </main>
</body>
</html>
