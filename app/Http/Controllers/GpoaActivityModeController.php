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
            'modes' = $modes
        ]);
    }

    public function confirmDestroy(GpoaActivityMode $mode)
    {
        return view('settings.gpoa-activities.delete-mode', [
            'mode' => $mode
        ]);
    }

    public function destroy(GpoaActivityMode $mode)
    {
        $mode->delete();
        return redirect()->route('gpoa-activities.modes.index')
            ->with('status', 'Activity mode deleted.');
    }
}
