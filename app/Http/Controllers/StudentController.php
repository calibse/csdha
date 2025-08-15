<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Validation\Rule;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;

class StudentController extends Controller
{
    public function index()
    {
        return view('students.index', [
            'students' => Student::all()
        ]);
    }

    public function create()
    {
        return view('students.create', [
            'courses' => Course::all(),
            'student' => null
        ]);
    }

    public function store(StoreStudentRequest $request)
    {
        self::storeOrUpdate($request);
        return redirect()->route('students.index');
    }

    private static function storeOrUpdate(Request $request, 
            Student $student = null)
    {
        if (!$student) {
            $student = new Student();
            $student->student_id = $request->student_id;
        }
        $student->first_name = $request->first_name;
        $student->middle_name = $request->middle_name;
        $student->last_name = $request->last_name;
        $student->suffix_name = $request->suffix_name;
        $student->course()->associate(Course::find($request->course));
        $student->year = $request->year;
        $student->section = $request->section;
        $student->email = $request->email;
        $student->save();
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Student $student)
    {
        return view('students.create', [
            'student' => $student,
            'courses' => Course::all()
        ]);
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        self::storeOrUpdate($request, $student);
        return redirect()->route('students.index');
    }

    public function destroy(string $id)
    {
        //
    }
}
