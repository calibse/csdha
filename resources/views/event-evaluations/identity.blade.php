<x-layout.multi-step-form :$formTitle :$eventName title="Identity" :$previousStepRoute>
    <x-alert/>
    <form id="current-form" method="post" action="{{ $submitRoute }}">
        @csrf
        {{--
        <p>
            <label>First name</label>
            <input name="first_name" value="{{ old('first_name') ?? ($inputs['first_name'] ?? null) }}">
        </p>
        <p>
            <label>Middle name (optional)</label>
            <input name="middle_name" value="{{ old('middle_name') ?? ($inputs['middle_name'] ?? null) }}">
        </p>
        <p>
            <label>Last name</label>
            <input name="last_name" value="{{ old('last_name') ?? ($inputs['last_name'] ?? null) }}">
        </p>
        <p>
            <label>Suffix name (optional)</label>
            <input name="suffix_name" value="{{ old('suffix_name') ?? ($inputs['suffix_name'] ?? null) }}">
        </p>
        --}}
        <p>
            <label>Student ID</label>
            <input name="student_id" value="{{ old('student_id') ?? ($inputs['student_id'] ?? null) }}">
        </p>
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
    </form>
</x-layout>
