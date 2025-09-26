<x-layout.multi-step-form :$formTitle :$eventName title="Acknowledgement" :$previousStepRoute :$lastStep :$submitRoute>
    <p>{{ $event->evalForm?->acknowledgement }}</p>
    <form id="current-form" method="post" action="{{ $submitRoute }}">
        @csrf
    </form>
    <x-slot:prevInput>
        <input type="hidden" name="token" value="{{ $token }}">
    </x-slot>
</x-layout>
