<x-layout.user :$backRoute class="events form" title="{{ $update ? 'Edit' : 'Add' }} Date">
    <article class="article">
        <x-alert errorBag="event-date_create" />
        <form method="POST" action="{{ $formAction }}">
        @if ($update)
            @method('PUT')
        @endif
        @csrf
            <div class="inline">
                <p>
                    <label>Date</label>
                    <input type="date" name="date" value="{{ old('date') ?? $date?->date->toDateString() }}">
                </p>
                <p>
                    <label>Start time</label>
                    <input type="time" name="start_time" value="{{ old('start_time') ?? $date?->start_time_short }}">
                </p>
                <p>
                    <label>End time</label>
                    <input type="time" name="end_time" value="{{ old('end_time') ?? $date?->end_time_short }}">
                </p>
            </div>
            <p>
                <button type="submit">{{ $update ? 'Update' : 'Save' }}</button>
            </p>
        </form>
    </article>
</x-layout.user>
