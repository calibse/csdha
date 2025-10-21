<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CS Event Evaluation Form</title>
	<link rel="icon" href="{{ asset('favicon.ico') . '?id=' . cache('website_logo_id') }}" />

    @vite(['resources/scss/app.scss'])
</head>
<body {{ $attributes }}>
    <header>
        <hgroup>
            <p class="main-brand org-name">
		<img class="logo" src="{{ asset('storage/organization-logo.png') . '?id=' . cache('organization_logo_id') }}">
                <span class="name">Computer Society</span>
            </p>
            <h1 class="title of-form">Event Evaluation</h1>
        </hgroup>
    </header>
    <section class="intro section">
        <img hidden>
        <h2 class="title">Introduction</h2>
        <pre>{{ $event->evalForm?->introduction }}</pre>
    </section>
@php
    $steps = [
        [
            'title' => 'Consent',
            'show_status' => true
        ],
        [
            'title' => 'Evaluation',
            'show_status' => true
        ],
        [
            'title' => 'Acknowledgement',
            'show_status' => true
        ],
        [
            'title' => 'Finish',
            'show_status' => false
        ],
    ];
@endphp
@foreach ($steps as $thisStep => $thisStepInfo)
    @if ($step === $thisStep)
    <main id="content" class="main section">
        <h2 class="title">{{ $thisStepInfo['title'] }}</h2>
        {{ $slot }}
    </main>
    @else
    <section class="section content">
        <h2 class="title"><a
            @if ($thisStep <= $completeSteps)
            href="{{ $routes[$thisStep] }}"
            @endif
        >{{ $thisStepInfo['title'] }}</a></h2>
        @if ($thisStep !== (count($steps) - 1))
        <p class="status">{{ ($thisStep < $completeSteps || $step === (count($steps) - 1)) ? 'Complete' : 'Incomplete' }}</p>
        @endif
    </section>
    @endif
@endforeach
</body>
</html>
