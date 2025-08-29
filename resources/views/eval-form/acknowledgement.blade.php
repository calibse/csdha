<x-layout.eval-form :$formTitle :$eventName title="Acknowledgement" :$previousStepRoute :$lastStep :$submitRoute>
    <p>{{ $event->evalForm?->acknowledgement }}</p>
    <form id="current-form" method="post" action="{{ $submitRoute }}">
        @csrf
    </form>
</x-layout.eval-form>