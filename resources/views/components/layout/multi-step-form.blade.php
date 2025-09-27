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
            <h1>{{ $formTitle }}</h1>
            <p>{{ $eventName }}</p>
        </hgroup>
    </header>
    <main {{ $attributes }}>
    @if ($title)
        <h2>{{ $title }}</h2>
    @endif
        {{ $slot }}
    @if ($previousStepRoute)
        <p class="submit-button">
            <button form="previous-form">Back</button>
            <button form="current-form">{{ $lastStep ? 'Submit' : 'Next' }}</button>
        </p>
    @elseif (!$end)
        <p class="submit-button">
            <button form="current-form">Next</button>
        </p>
    @endif
    </main>
@if ($previousStepRoute)
    <form id="previous-form" method="get" action="{{ $previousStepRoute }}">
    @if (isset($prevInput) && $prevInput->hasActualContent())
        {{ $prevInput }}
    @endif
    </form>
@endif
</body>
</html>
