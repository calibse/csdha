<x-layout.user form title="Add Attendee" :$backRoute class="events form" >
    <article class="article">
        <x-alert/>
        <form id="current-form" method="post" action="{{ $submitRoute }}">
            @csrf
            <p>
                <label>Event date</label>
                <select name="date">
                    @if ($dates->count() !== 1)
                    <option value="">-- Select --</option>
                    @endif
                    @foreach ($dates as $date)
                    <option value="{{ $date->public_id }}" {{ (old('date') ?? null) === (string) $date->public_id ? 'selected' : null }}>
                        {{ $date->full_date }}
                    </option>
                    @endforeach
                </select>
            </p>
            <p>
                <label>Officers</label>
                <select multiple size="{{ $officers->count() }}" name="officers[]">
                    @foreach ($officers as $officer)
                    <option value="{{ $officer->public_id }}">{{ $officer->full_name }} ({{ $officer->position->name }})</option>
                    @endforeach
                </select>
            </p>
            <p class="form-submit">
                <button>Save</button>
            </p>
        </form>
    </article>
</x-layout.user>
