<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CSDHA</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <style>
        body {
            margin: 1rem;
        }
    </style>
</head>
<body class="event-regisration">
    <h1>Event Registration Form</h1>
    <h2>{{ $activity->name }}</h2>
    <p>Please enter your student ID to be registered for this event.</p>
    <x-alert/>
    <form method="post" action="{{ $formAction }}">
        @csrf
        <p>
            <label>Student ID</label>
            <input name="student_id">
        </p>
        <p class="form-submit">
            <button>Submit</button>
        </p>
    </form>
</body>
</html>