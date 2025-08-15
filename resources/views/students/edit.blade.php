<x-layout.user route="students.index" title="Edit student" class="students form">
    <article class="article">
        <x-alert/>
        <form method="post" action="{{ route('students.update', ['student' => $student->id ]) }}">
            @method('PUT')
            @csrf
            <p>
                <label>Student ID</label>
                <input disabled value="{{ $student->student_id }}">
            </p>
            <p>
                <label>First Name</label>
                <input name="first_name" value="{{ $student->first_name }}">
            </p>
            <p>
                <label>Middle Name</label>
                <input name="middle_name" value="{{ $student->middle_name }}">
            </p>
            <p>
                <label>Last Name</label>
                <input name="last_name" value="{{ $student->last_name }}">
            </p>
            <p>
                <label>Suffix Name</label>
                <input name="suffix_name" value="{{ $student->suffix_name }}">
            </p>
            <p>
                <label>Course</label>
                <select required name="course">
                    <option value="">Select Course</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" {{ $course->is($student->course) ? 'selected' : ''}}>
                        {{ $course->acronym }} - {{ $course->name }}
                    </option>
                @endforeach
                </select>
            </p>
            <p>
                <label>Year</label>
                <input type="number" name="year" value="{{ $student->year }}">
            </p>
            <p>
                <label>Section</label>
                <input name="section" value="{{ $student->section }}">
            </p>
            <p>
                <label>Email</label>
                <input type="email" name="email" value="{{ $student->email }}">
            </p>
            <p class="form-submit">
                <button>Update</button>
            </p>
        </form>
    </article>
</x-layout.user>