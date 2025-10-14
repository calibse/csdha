<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentSection;
use App\Http\Requests\StoreStudentSectionRequest;

class StudentSectionController extends Controller
{
    public function index()
    {
        $sections = StudentSection::all();
        return view('settings.student-sections.index', [
            'sections' => $sections,
            'backRoute' => route('settings.index'),
            'createSectionRoute' => route('settings.students.sections.create'),
        ]); 
    }

    public function create()
    {
        return view('settings.student-sections.create', [
            'backRoute' => route('settings.students.sections.index'),
            'formAction' => route('settings.students.sections.store'),
        ]); 
    }

    public function store(StoreStudentSectionRequest $request)
    {
        $section = new StudentSection;
        $section->section = $request->section;
        $section->save();
        return view('settings.student-sections.index')->with('status', 
            'Student section added.'); 
    }

    public function confirmDestroy(StudentSection $section)
    {
        return view('settings.student-sections.delete', [
            'section' => $section,
            'formAction' => route('settings.students.sections.destroy', [
                'section' => $section->id
            ]),
            'backRoute' => route('settings.students.sections.index'),
        ]);
    }

    public function destroy(StudentSection $section)
    {
        $section->delete();
        return redirect()->route('settings.student-sections.index')
            ->with('status', 'Student section deleted.'); 
    }
}
