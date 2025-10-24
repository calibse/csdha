<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Gpoa;
use App\Models\Event;
use App\Models\AccomReport;
use App\Services\Stream;

class HomeController extends Controller
{
    public function index() 
    {
        $gpoaActive = Gpoa::active()->exists();
        $gpoa = Gpoa::active()->first();
	if (!$gpoa) {
            return view('home.user', [
                'gpoaActive' => false,
                'pendingAccomReportCount' => 0,
                'pendingGpoaActivityCount' => 0,
                'upcomingEventCount' => 0,
                'gpoaRoute' => route('gpoa.index'),
                'eventsRoute' => route('events.index'),
                'accomReportsRoute' => route('accom-reports.index'),
            ]);
        }
        return view('home.user', [
            'gpoaActive' => true,
            'pendingAccomReportCount' => AccomReport::active()
                ->where('status', 'pending')->count(),
            'pendingGpoaActivityCount' => $gpoa->activities()
                ->where('status', 'pending')->count(),
            'upcomingEventCount' => Event::active()->upcoming()->count(),
            'gpoaRoute' => route('gpoa.index'),
            'eventsRoute' => route('events.index'),
            'accomReportsRoute' => route('accom-reports.index'),
        ]);
    }

    public function adminIndex() 
    {
        return view('home.admin');
    }

    public function stream()
    {
        return Stream::event('home_stream');
    }
}
