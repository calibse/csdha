<x-layout.event-registration-form :$eventName :$step :$completeSteps :$routes>
    <p>{{ $event->regisForm?->introduction }}</p>
    <h3>Data Privacy Act of 2012</h3>
    <x-alert/>
    <form id="current-form" method="post" action="{{ $submitRoute }}">
        @csrf
        <x-consent :value="$errors->any() ? old('consent') : ($inputs['consent'] ?? null)" />
        <p class="form-submit">
            <button>Next</button>
        </p>
    </form>
</x-layout>
