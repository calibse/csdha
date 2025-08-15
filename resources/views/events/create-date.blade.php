@php
$routeParams = ['event' => $event->public_id];
$formAction = $update 
    ? route('events.dates.update', [
        'event' => $event->public_id, 
        'date' => $date->public_id
    ])
    : route('events.dates.store', ['event' => $event->public_id]);
@endphp
<x-layout.user route="events.dates.index" :$routeParams class="events form" title="{{ $update ? 'Edit' : 'Add' }} Date">
    <article class="article">
        <x-alert/>
        <form method="POST" action="{{ $formAction }}">
        @if ($update)
            @method('PUT')
        @endif
        @csrf
            <p>
                <label>Date</label>
                <input type="date" name="date" value="{{ old('date') ?? $date?->date }}">
            </p>
            <p>
                <label>Start time</label>
                <input type="time" name="start_time" value="{{ old('start_time') ?? $date?->start_time_short }}">
            </p>
            <p>
                <label>End time</label>
                <input type="time" name="end_time" value="{{ old('end_time') ?? $date?->end_time_short }}">
            </p>
            <p class="form-submit">
                <button type="submit">{{ $update ? 'Update' : 'Save' }}</button>
            </p>
        </form>
    </article>
</x-layout.user>
