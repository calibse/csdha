<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentSection;
use App\Http\Requests\StoreStudentSectionRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class StudentSectionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth.setting:create,'. StudentSection::class, 
                only: [
                'create', 'store'
            ]),
            new Middleware('auth.setting:delete,section', only: [
                'confirmDestroy', 'destroy'
            ]),
        ];
    }

    public function index()
    {
        $sections = StudentSection::all();
        return view('settings.student-sections.index', [
            'sections' => $sections,
            'backRoute' => route('settings.index'),
            'createSectionRoute' => route('settings.students.sections.create'),
            'createFormAction' => route('settings.students.sections.store'),
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
        return redirect()->route('settings.students.sections.index')
            ->with('status', 'Student section added.'); 
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
        return redirect()->route('settings.students.sections.index')
            ->with('status', 'Student section deleted.'); 
    }
}
