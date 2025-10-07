<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GpoaActivityFundSource;

class GpoaActivityFundSourceController extends Controller
{
    public function index()
    {
        $funds = GpoaActivityFundSource::all();
        return view('settings.gpoa-activities.index-funds', [
            'funds' = $funds
        ]);
    }

    public function confirmDestroy(GpoaActivityFundSource $fund)
    {
        return view('settings.gpoa-activities.delete-fund', [
            'fund' => $fund
        ]);
    }

    public function destroy(GpoaActivityFundSource $fund)
    {
        $fund->delete();
        return redirect()->route('gpoa-activities.funds.index')
            ->with('status', 'Activity fund source deleted.');
    }
}
