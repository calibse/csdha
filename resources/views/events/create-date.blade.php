<x-layout.user content-view :$backRoute class="events form" title="{{ $update ? 'Edit' : 'Add' }} Date">
    <div class="article">
        <x-alert errorBag="event-date_create" />
        <form method="POST" action="{{ $formAction }}">
        @if ($update)
            @method('PUT')
        @endif
        @csrf
            <div class="inline">
                <p>
                    <label>Date</label>
                    <input required pattern="^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$" type="date" name="date" value="{{ old('date') ?? $date?->date->toDateString() }}">
                </p>
                <p>
                    <label>Start time</label>
                    <input required pattern="^([01]\d|2[0-3]):([0-5]\d)$" type="time" name="start_time" value="{{ old('start_time') ?? $date?->start_time_short }}">
                </p>
                <p>
                    <label>End time</label>
                    <input required pattern="^([01]\d|2[0-3]):([0-5]\d)$" type="time" name="end_time" value="{{ old('end_time') ?? $date?->end_time_short }}">
                </p>
            </div>
            <p>
                <button type="submit">{{ $update ? 'Update' : 'Save' }}</button>
            </p>
        </form>
    </div>
</x-layout.user>
