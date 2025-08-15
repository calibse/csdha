@php
$routeParams = ['event' => $event->id];

@endphp

<x-layout.user 
    class="events" 
    route="events.createAccomReport" 
    :$routeParams title="Generated Accomplishment Report"
>
    <p class="main-action">
        <a href="{{ route('events.saveGenAccomReport', ['event' => $event->id, 'cacheKey' => $cacheKey], false) }}">
            <span class="icon">
                <x-icon.check/>
            </span>
            Save
        </a>
    </p>
    <figure class="document-view">
        <embed type="application/pdf" 
            src="{{ route('events.viewGenAccomReport', ['event' => $event->id, 'cacheKey' => $cacheKey], false) }}">
        <figcaption>Generated Accomplishment Report</figcaption>
    </figure>
</x-layout.user>
