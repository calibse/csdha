<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('settings.index', [
            'studentSectionsRoute' => route('settings.students.sections.index'),
            'studentYearsRoute' => route('settings.students.years.index'),
            'studentCoursesRoute' => route('settings.students.courses.index'),
            'gpoaModesRoute' => route('settings.gpoa-activities.modes.index'),
            'gpoaFundSourcesRoute' => route('settings.gpoa-activities.fund-sources.index'),
            'gpoaPartnershipsRoute' => route('settings.gpoa-activities.partnership-types.index'),
            'gpoaTypesRoute' => route('settings.gpoa-activities.types.index'),
        ]);
    }
}
