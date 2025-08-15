<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticController extends Controller
{
    public function __construct()
    {
        $this->timezone = 'Asia/Manila';
    }

    public function index()
    {
        $lastActivity = Carbon::createFromTimestamp(DB::table('sessions')->select('last_activity')->orderBy('last_activity', 'desc')->first()->last_activity, $this->timezone)->toDayDateTimeString();
        return view('analytics.index', [
            'lastActivity' => $lastActivity,
        ]);        
    }
}
