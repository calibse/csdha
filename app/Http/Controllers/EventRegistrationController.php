<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QrCode;
use Illuminate\Support\Str;
use App\Models\Event;
use App\Models\Student;
use App\Models\EventStudent;
use App\Models\EventRegistration;
use App\Http\Requests\StoreEventRegistrationRequest;

class EventRegistrationController extends Controller
{
    public function index()
    {
        //
    }

    public function create(Event $event)
    {
        return view('event-registration.create', [
            'event' => $event,
            'activity' => $event->gpoaActivity,
            'formAction' => route('events.registrations.store', [
                'event' => $event->public_id
            ]),
        ]);
    }

    public function store(StoreEventRegistrationRequest $request, Event $event)
    {
        $student = new EventStudent();
        $student->first_name = $request->first_name;
        $student->middle_name = $request->middle_name;
        $student->last_name = $request->last_name;
        $student->suffix_name = $request->suffix_name;
        $student->course()->associate(Course::find($request->course));
        $student->year = $request->year;
        $student->section = $request->section;
        $student->email = $request->email;
        $student->save();
        $regis = new EventRegistration();
        $regis->token = Str::ulid();
        $regis->event()->associate($event);
        $regis->student()->associate($student);
        $regis->save();
        $qrCode = new QrCode($regis->token, $event->tag, $student->student_id);
        return view('event-registration.result', [
            'event' => $event,
            'activity' => $event->gpoaActivity,
            'qrCode' => $qrCode->toSvg()
        ]);
    }

    public function showQrCode(Request $request, Event $event)
    {
        $currentRoute = $request->route()->getName();
        $data = session($currentRoute, null); 
        if (!$data) abort(404);
        $qrCode = new QrCode($data['token'], $data['tag'], $data['studentId']);
        return $qrCode->stream();
    }

    public function result(Event $event)
    {
    }

    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
