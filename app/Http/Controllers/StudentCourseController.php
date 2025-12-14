<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Http\Requests\StoreStudentCourseRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class StudentCourseController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth.setting:create,' . Course::class, only: [
                'create', 'store'
            ]),
            new Middleware('auth.setting:delete,course', only: [
                'confirmDestroy', 'destroy'
            ]),
        ];
    }

    public function index()
    {
        $courses = Course::all();
        return view('settings.student-courses.index', [
            'courses' => $courses,
            'backRoute' => route('settings.index'),
            'createRoute' => route('settings.students.courses.create'),
            'createFormAction' => route('settings.students.courses.store'),
        ]); 
    }

    public function create()
    {
        return view('settings.student-courses.create', [
            'backRoute' => route('settings.students.courses.index'),
            'formAction' => route('settings.students.courses.store'),
        ]);

    }

    public function store(StoreStudentCourseRequest $request)
    {
        $course = new Course;
        $course->name = $request->name;
        $course->acronym = $request->acronym;
        $course->save();
        return redirect()->route('settings.students.courses.index')
            ->with('status', 'Student course added.'); 
    }

    public function confirmDestroy(Course $course)
    {
        return view('settings.student-courses.delete', [
            'course' => $course,
            'formAction' => route('settings.students.courses.destroy', [
                'course' => $course->id
            ]),
            'backRoute' => route('settings.students.courses.index'),
        ]);
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('settings.students.courses.index')
            ->with('status', 'Student course deleted.'); 
    }
}
