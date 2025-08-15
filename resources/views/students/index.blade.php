<x-layout.user index class="index students" title="Students">
    <x-slot:toolbar>
    @can ('create', 'App\Models\Student')
        <a href="{{ route('students.create', [], false) }}" >
            <span class="icon"><x-phosphor-plus-circle/></span>
            <span class="text">Add Student</span>
        </a>
        <a href="{{ route('courses.create', [], false) }}" >
            <span class="icon"><x-phosphor-plus-circle/></span>
            <span class="text">Add Course</span>
        </a>
    @endcan
    </x-slot:toolbar>
    <article class="table-block">
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Course & Year</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                <tr>
                    <td>
                        <a href="{{ route('students.edit', ['student' => $student->public_id ]) }}">
                            {{ $student->student_id }}
                        </a>
                    </td>
                    <td>{{ $student->fullName }}</td>
                    <td>{{ $student->courseSection }}</td>
                    <td>{{ $student->email }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </article>
</x-layout.user>