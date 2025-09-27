<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CS Event Registration</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <style>
        body {
            margin: 1rem;
        }
    </style>
</head>
<body>
    <header>
        <hgroup>
            <p>Computer Society</p>
            <h1>Event Registration</h1>
        </hgroup>
    </header>
    <section>
        <h2>Event Details</h2>
        <p>Name: {{ $eventName }}</p>
    </section>
@php $thisStep = 0; @endphp
@if ($step === $thisStep)
    <main id="content">
        <h2>Consent</h2>
        {{ $slot }}
    </main>
@else
    <section>
        <h2><a
        @if ($thisStep <= $completeSteps)
            href="{{ $routes[$thisStep] }}"
        @endif
        >Consent</a></h2>
        <p>{{ $thisStep < $completeSteps ? 'Complete' : 'Incomplete' }}</p>
    </section>
@endif
@php $thisStep = 1; @endphp
@if ($step === $thisStep)
    <main id="content">
        <h2>Identity</h2>
        {{ $slot }}
    </main>
@else
    <section>
        <h2><a
        @if ($thisStep <= $completeSteps)
            href="{{ $routes[$thisStep] }}"
        @endif
        >Identity</a></h2>
        <p>{{ $thisStep < $completeSteps ? 'Complete' : 'Incomplete' }}</p>
    </section>
@endif
@php $thisStep = 2; @endphp
@if ($step === $thisStep)
    <main id="content">
        <h2>Finish</h2>
        {{ $slot }}
    </main>
@else
    <section>
        <h2>Finish</h2>
    </section>
@endif
</body>
</html>
