<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GpoaActivityPartnershipType;

class GpoaActivityPartnershipTypeController extends Controller
{
    public function index()
    {
        $partnerships = GpoaActivityPartnershipType::all();
        return view('settings.gpoa-activities.index-partnerships', [
            'partnerships' = $partnerships
        ]);
    }

    public function confirmDestroy(GpoaActivityPartnershipType $partnership)
    {
        return view('settings.gpoa-activities.delete-partnership', [
            'partnership' => $partnership
        ]);
    }

    public function destroy(GpoaActivityPartnershipType $partnership)
    {
        $partnership->delete();
        return redirect()->route('gpoa-activities.partnerships.index')
            ->with('status', 'Activity partnership type deleted.');
    }
}
