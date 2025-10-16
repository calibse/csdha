<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GpoaActivityFundSource;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class GpoaActivityFundSourceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth.setting:delete,fund', only: [
                'confirmDestroy', 'destroy'
            ]),
        ];
    }

    public function index()
    {
        $funds = GpoaActivityFundSource::all();
        return view('settings.gpoa-activities.index-funds', [
            'funds' => $funds,
            'backRoute' => route('settings.index'),
        ]);
    }

    public function confirmDestroy(GpoaActivityFundSource $fund)
    {
        return view('settings.gpoa-activities.delete-fund', [
            'fund' => $fund,
            'formAction' => route('settings.gpoa-activities.fund-sources.destroy', [
                'fund' => $fund->id
            ]),
            'backRoute' => route('settings.gpoa-activities.fund-sources.index'),
        ]);
    }

    public function destroy(GpoaActivityFundSource $fund)
    {
        $fund->delete();
        return redirect()->route('gpoa-activities.funds.index')
            ->with('status', 'Activity fund source deleted.');
    }
}
