<x-layout.multi-step-form :$formTitle :$eventName :$submitRoute>
    <p>{{ $event->evalForm?->introduction }}</p>
    <h2>Data Privacy Act of 2012</h2>
    <x-alert/>
    <form id="current-form" method="post" action="{{ $submitRoute }}">
        @csrf
        <x-consent :value="$inputs['consent'] ?? null" />
    </form>
    <x-slot:prevInput>
        <input type="hidden" name="token" value="{{ $token }}">
    </x-slot>
</x-layout>
