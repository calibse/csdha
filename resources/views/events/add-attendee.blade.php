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
                <label>Email</label>
                <input name="email" value="{{ old('email') ?? null }}">
            </p>
            <p>
                <label>First name</label>
                <input name="first_name" value="{{ old('first_name') ?? null }}">
            </p>
            <p>
                <label>Middle name (optional)</label>
                <input name="middle_name" value="{{ old('middle_name') ?? null }}">
            </p>
            <p>
                <label>Last name</label>
                <input name="last_name" value="{{ old('last_name') ?? null }}">
            </p>
            <p>
                <label>Suffix name (optional)</label>
                <input name="suffix_name" value="{{ old('suffix_name') ?? null }}">
            </p>
            <p>
                <label>Student ID</label>
                <input name="student_id" value="{{ old('student_id') ?? null }}">
            </p>
            <p>
                <label>Program</label>
                <select name="program">
                    <option value="">-- Select --</option>
                    @foreach ($programs as $program)
                    <option value="{{ $program->id }}" {{ (old('program') ?? null) === (string)$program->id ? 'selected' : null }}>
                        {{ $program->acronym . ' - ' . $program->name }}
                    </option>
                    @endforeach
                </select>
            </p>
            <p>
                <label>Year level</label>
                <select name="year_level">
                    <option value="">-- Select --</option>
                    @foreach ($yearLevels as $yearLevel)
                    <option value="{{ $yearLevel->id }}" {{ (old('year_level') ?? null) === (string)$yearLevel->id ? 'selected' : null }}>
                        {{ $yearLevel->label }}
                    </option>
                    @endforeach
                </select>
            </p>
            <p>
                <label>Section</label>
                <input name="section" value="{{ old('section') ?? null }}">
            </p>
            <p class="form-submit">
                <button>Save</button>
            </p>
        </form>
    </article>
</x-layout.user>
