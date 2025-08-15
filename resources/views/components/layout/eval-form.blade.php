<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CS Event {{ $formTitle }}</title>
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
            <p>{{ $eventName }}</p>
            <h1>{{ $formTitle }}</h1>
        </hgroup>
    </header> 
    <main>
        @if ($title)
        <h2>{{ $title }}</h2>
        @endif
        {{ $slot }}
        @if ($previousStepRoute)
        <form method="get" action="{{ $previousStepRoute }}">
            <p class="form-submit">
                <button>Back</button>
                <button form="current-form">{{ $lastStep ? 'Submit' : 'Next' }}</button>
            </p>
        </form>
        @elseif (!$end)
        <p class="submit-button">
            <button form="current-form">Next</button>
        </p>
        @endif
    </main>
</body>
</html>