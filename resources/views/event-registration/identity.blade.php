<x-layout.event-registration-form class="event-registration multi-step-form" :$event :$step :$completeSteps :$routes>
    <x-alert/>
    <form id="current-form" method="post" action="{{ $submitRoute }}">
        @csrf
        <p>
            <label>Email</label>
            <input name="email" value="{{ old('email') ?? ($inputs['email'] ?? null) }}">
        </p>
        <p>
            <label>First name</label>
            <input name="first_name" value="{{ old('first_name') ?? ($inputs['first_name'] ?? null) }}">
        </p>
        <p>
            <label>Middle name</label>
            <input name="middle_name" value="{{ old('middle_name') ?? ($inputs['middle_name'] ?? null) }}">
        </p>
        <p>
            <label>Last name</label>
            <input name="last_name" value="{{ old('last_name') ?? ($inputs['last_name'] ?? null) }}">
        </p>
        <p>
            <label>Suffix name</label>
            <input name="suffix_name" value="{{ old('suffix_name') ?? ($inputs['suffix_name'] ?? null) }}">
        </p>
        <p>
            <label>Student ID</label>
            <input name="student_id" value="{{ old('student_id') ?? ($inputs['student_id'] ?? null) }}">
        </p>
        <fieldset>
            <legend>Program</legend>
        @foreach ($programs as $program)
            <p>
                <input id="program-{{ $program->id }}" name="program" type="radio" value="{{ $program->id }}" {{ (old('program') ?? ($inputs['program'] ?? null)) === (string)$program->id ? 'checked' : null }}>
                <label for="program-{{ $program->id }}">{{ $program->acronym . ' - ' . $program->name }}</label>
            </p>
        @endforeach
        </fieldset>
        <fieldset>
            <legend>Year level</legend>
        @foreach ($yearLevels as $yearLevel)
            <p name="year_level">
                <input id="year-level-{{ $yearLevel->id }}" name="year_level" type="radio" value="{{ $yearLevel->id }}" {{ (old('year_level') ?? ($inputs['year_level'] ?? null)) === (string)$yearLevel->id ? 'checked' : null }}>
                <label for="year-level-{{ $yearLevel->id }}">{{ $yearLevel->label }}</label>
            </select>
        @endforeach
        </fieldset>
        {{--
        <p>
            <label>Program</label>
            <select name="program">
                <option value="">-- Select --</option>
                @foreach ($programs as $program)
                <option value="{{ $program->id }}" {{ (old('program') ?? ($inputs['program'] ?? null)) === (string)$program->id ? 'selected' : null }}>
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
                <option value="{{ $yearLevel->id }}" {{ (old('year_level') ?? ($inputs['year_level'] ?? null)) === (string)$yearLevel->id ? 'selected' : null }}>
                    {{ $yearLevel->label }}
                </option>
                @endforeach
            </select>
        </p>
        --}}
        <p>
            <label>Section</label>
            <input name="section" value="{{ old('section') ?? ($inputs['section'] ?? null) }}">
        </p>
        <p class="form-submit">
            <button>Next</button>
        </p>
    </form>
</x-layout>
