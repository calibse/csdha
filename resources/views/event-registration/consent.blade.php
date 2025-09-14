<x-layout.multi-step-form :$eventName :$formTitle :$submitRoute>
    <p>{{ $event->regisForm?->introduction }}</p>
    <h2>Data Privacy Act of 2012</h2>
    <x-alert/>
    <form id="current-form" method="post" action="{{ $submitRoute }}">
        @csrf
        <x-consent/>
    </form>
</x-layout.multi-step-form>
