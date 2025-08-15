<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\EventDate;
use App\Models\EventRegistration;
use App\Models\EventAttendance;
use App\Models\EventAttendee;
use App\Http\Requests\StoreAttendanceRequest;

class AttendanceController extends Controller
{
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
        if (EventAttendee::whereBelongsTo($student, 'student')
            ->whereBelongsTo($eventDate)->exists()) {
            return response([], 200);
        }
        $attendee = new EventAttendee();
        $attendee->eventDate()->associate($eventDate);
        $attendee->student()->associate($student);
        $attendee->save();
        return response([], 200);
    }
}
