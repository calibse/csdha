<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function create()
    {
        return view('courses.create', [
            'courses' => Course::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['unique:courses'],
            'acronym' => ['unique:courses'],
        ]);

        $course = new Course();
        $course->name = $request->name;
        $course->acronym = $request->acronym;
        $course->save();

        return redirect()->route('courses.create');
    }

    public function update(Request $request, string $id)
    {
        $course = Course::find($id);
        $course->name = $request->name;
        $course->save();

        return redirect()->route('courses.create');
    }

    public function destroy(string $id)
    {
        //
    }
}
