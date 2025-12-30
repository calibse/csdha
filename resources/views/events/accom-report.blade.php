@use('App\Services\Format')
@php
if (!isset($browser)) $browser = false;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<title>Accomplishment Report</title>
@if ($browser)
@vite(['resources/scss/accom-report-web.scss'])
@else
<style>
{!! Vite::content('resources/scss/accom-report-print.scss') !!}
</style>
@endif
</head>
<body>
</body>
</html>
