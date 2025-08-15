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
<body class="event-registration">
    <h1>Successful Event Registration</h1>
    <h2>{{ $activity->name }}</h2>
    <p>Use the QR code below to record your attendance</p>
    <p class="qr-code">{!! $qrCode !!}</p>
</body>
</html>