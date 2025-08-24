<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\EventDate;
use App\Models\EventRegistration;
use App\Models\EventAttendance;
use App\Models\EventAttendee;
use App\Http\Requests\StoreAttendanceRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AttendanceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:storeAttendance,event_date', only: ['store'])
        ];
    }
    
    public function create()
    {
        $dates = EventDate::ongoing()->get();
        return view('attendance.show', [
            'dates' => $dates
        ]);
    }

    public function store(StoreAttendanceRequest $request, 
            EventDate $eventDate) 
    {
        $regis = EventRegistration::where('token', $request->token)
            ->whereBelongsTo($eventDate->event)->first();
        if (!$regis) {
            return response([], 404);
        }
        $student = $regis->student;
        $event = $eventDate->event;
        if ($event->dates()->whereAttachedTo($student, 'attendees')->exists()) {
            return response([], 200);
        }
        $eventDate->attendees()->attach($student);
        $eventDate->save();
        return response([], 200);
    }
}
