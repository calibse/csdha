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
            'types' = $types
        ]);
    }

    public function confirmDestroy(GpoaActivityType $type)
    {
        return view('settings.gpoa-activities.delete-type', [
            'type' => $type
        ]);
    }

    public function destroy(GpoaActivityType $type)
    {
        $type->delete();
        return redirect()->route('gpoa-activities.types.index')
            ->with('status', 'Activity type deleted.');
    }
}
