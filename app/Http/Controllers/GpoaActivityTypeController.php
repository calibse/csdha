<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GpoaActivityType;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class GpoaActivityTypeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth.setting:delete,type', only: [
                'confirmDestroy', 'destroy'
            ]),
        ];
    }

    public function index()
    {
        $types = GpoaActivityType::orderBy('created_at', 'desc')->get();
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
        return redirect()->route('settings.gpoa-activities.types.index')
            ->with('status', 'Activity type deleted.');
    }
}
