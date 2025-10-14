<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GpoaActivityMode;

class GpoaActivityModeController extends Controller
{
    public function index()
    {
        $modes = GpoaActivityMode::all();
        return view('settings.gpoa-activities.index-modes', [
            'modes' => $modes,
            'backRoute' => route('settings.index'),
        ]);
    }

    public function confirmDestroy(GpoaActivityMode $mode)
    {
        return view('settings.gpoa-activities.delete-mode', [
            'mode' => $mode,
            'formAction' => route('settings.gpoa-activities.modes.destroy', [
                'mode' => $mode->id
            ]),
            'backRoute' => route('settings.gpoa-activities.modes.index'),
        ]);
    }

    public function destroy(GpoaActivityMode $mode)
    {
        $mode->delete();
        return redirect()->route('gpoa-activities.modes.index')
            ->with('status', 'Activity mode deleted.');
    }
}
