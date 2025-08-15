@php
$routeParams = $student ? [
    'student' => $student->public_id
] : [];
$formAction = $student 
    ? route('students.update', ['student' => $student->public_id])
    : route('students.store');
@endphp
<x-layout.user route="students.index" title="{{ $student ? 'Edit student' : 'Add student' }}" class="students form">
    <article class="article">
        <x-alert/>
        <form method="post" action="{{ $formAction }}">
        @if ($student)
            @method('PUT')
        @endif
            @csrf
            <p>
                <label>Student ID</label>
                <input {{ $student ? 'disabled' : null }} placeholder="ex: 2025-00000-PH-0" name="{{ $student ? null : 'student_id' }}" value="{{ old('student_id') ?? $student?->student_id }}">
            </p>
            <p>
                <label>First Name</label>
                <input name="first_name" value="{{ old('first_name') ?? $student?->first_name }}">
            </p>
            <p>
                <label>Middle Name</label>
                <input name="middle_name" value="{{ old('middle_name') ?? $student?->middle_name }}">
            </p>
            <p>
                <label>Last Name</label>
                <input name="last_name" value="{{ old('last_name') ?? $student?->last_name }}">
            </p>
            <p>
                <label>Suffix Name</label>
                <input name="suffix_name" value="{{ old('suffix_name') ?? $student?->suffix_name }}">
            </p>
            <p>
                <label>Course</label>
                <select name="course">
                    <option value="">Select Course</option>
                @foreach ($courses as $course)
                    @if (old('course') && old('course') === (string)$course->id)
                    <option selected value="{{ $course->id }}">{{ $course->acronym }} - {{ $course->name }}</option>
                    @elseif ($student && $student->course()->is($course))
                    <option selected value="{{ $course->id }}">{{ $course->acronym }} - {{ $course->name }}</option>
                    @else
                    <option value="{{ $course->id }}">{{ $course->acronym }} - {{ $course->name }}</option>
                    @endif
                @endforeach
                </select>
            </p>
            <p>
                <label>Year</label>
                <input name="year" value="{{ old('year') ?? $student?->year }}">
            </p>
            <p>
                <label>Section</label>
                <input name="section" value="{{ old('section') ?? $student?->section }}">
            </p>
            <p>
                <label>Email</label>
                <input name="email" value="{{ old('email') ?? $student?->email }}">
            </p>
            <p class="form-submit">
                <button>Save</button>
            </p>
        </form>
    </article>
</x-layout.user>