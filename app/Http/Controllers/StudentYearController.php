<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentYear;
use App\Http\Requests\StoreStudentYearRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class StudentYearController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth.setting:create,' . StudentYear::class, only: [
                'create', 'store'
            ]),
            new Middleware('auth.setting:delete,year', only: [
                'confirmDestroy', 'destroy'
            ]),
        ];
    }

    public function index()
    {
        $years = StudentYear::all();
        return view('settings.student-years.index', [
            'years' => $years,
            'backRoute' => route('settings.index'),
            'createRoute' => route('settings.students.years.create'),
        ]); 
    }

    public function create()
    {
        return view('settings.student-years.create', [
            'backRoute' => route('settings.students.years.index'),
            'formAction' => route('settings.students.years.store'),
        ]);

    }

    public function store(StoreStudentYearRequest $request)
    {
        $year = new StudentYear;
        $year->year = $request->year;
        $year->label = $request->label;
        $year->save();
        return view('settings.student-years.index')->with('status', 
            'Student year added.'); 
    }

    public function confirmDestroy(StudentYear $year)
    {
        return view('settings.student-years.delete', [
            'year' => $year,
            'formAction' => route('settings.students.years.destroy', [
                'year' => $year->id
            ]),
            'backRoute' => route('settings.students.years.index'),
        ]);
    }

    public function destroy(StudentYear $year)
    {
        $year->delete();
        return redirect()->route('settings.student-years.index')
            ->with('status', 'Student year deleted.'); 
    }
}
