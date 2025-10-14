<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GpoaActivityType;

class GpoaActivityTypeController extends Controller
{
    public function index()
    {
        $types = GpoaActivityType::all();
        return view('settings.gpoa-activities.index-types', [
            'types' => $types,
            'backRoute' => route('settings.index'),
        ]);
    }

    public function confirmDestroy(GpoaActivityType $type)
    {
        return view('settings.gpoa-activities.delete-type', [
            'type' => $type,
            'formAction' => route('settings.gpoa-activities.types.destroy', [
                'type' => $type->id
            ]),
            'backRoute' => route('settings.gpoa-activities.types.index'),
        ]);
    }

    public function destroy(GpoaActivityType $type)
    {
        $type->delete();
        return redirect()->route('gpoa-activities.types.index')
            ->with('status', 'Activity type deleted.');
    }
}
