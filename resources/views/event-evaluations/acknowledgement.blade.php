<x-layout.event-evaluation-form class="event-evaluation multi-step-form" :$event :$step :$completeSteps :$routes>
    <p>{{ $event->evalForm?->acknowledgement }}</p>
    <form id="current-form" method="post" action="{{ $submitRoute }}">
        @csrf
        <p class="form-submit">
            <button>Next</button>
        </p>
    </form>
</x-layout>
