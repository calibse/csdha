<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GpoaActivityMode;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class GpoaActivityModeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth.setting:delete,mode', only: [
                'confirmDestroy', 'destroy'
            ]),
        ];
    }

    public function index()
    {
        $modes = GpoaActivityMode::orderBy('created_at', 'desc')->get();
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
        return redirect()->route('settings.gpoa-activities.modes.index')
            ->with('status', 'Activity mode deleted.');
    }
}
