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
            ]);
        }
        return view('home.user', [
            'gpoaActive' => true,
            'pendingAccomReportCount' => AccomReport::active()
                ->where('status', 'pending')->count(),
            'pendingGpoaActivityCount' => $gpoa->activities()
                ->where('status', 'pending')->count(),
            'upcomingEventCount' => Event::active()->upcoming()->count()
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
