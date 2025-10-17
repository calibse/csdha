<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GpoaActivityPartnershipType;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class GpoaActivityPartnershipTypeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth.setting:delete,partnership', only: [
                'confirmDestroy', 'destroy'
            ]),
        ];
    }

    public function index()
    {
        $partnerships = GpoaActivityPartnershipType::orderBy('created_at', 
            'desc')->get();
        return view('settings.gpoa-activities.index-partnerships', [
            'partnerships' => $partnerships,
            'backRoute' => route('settings.index'),
        ]);
    }

    public function confirmDestroy(GpoaActivityPartnershipType $partnership)
    {
        return view('settings.gpoa-activities.delete-partnership', [
            'partnership' => $partnership,
            'formAction' => route('settings.gpoa-activities.partnership-types.destroy', [
                'partnership' => $partnership->id
            ]),
            'backRoute' => route('settings.gpoa-activities.partnership-types.index'),
        ]);
    }

    public function destroy(GpoaActivityPartnershipType $partnership)
    {
        $partnership->delete();
        return redirect()->route('settings.gpoa-activities.partnership-types.index')
            ->with('status', 'Activity partnership type deleted.');
    }
}
