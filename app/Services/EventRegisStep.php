<?php

namespace App\Services;

use App\Models\Course;
use App\Models\StudentYear;
use App\Models\Event;
use App\Models\EventStudent;
use App\Models\EventRegistration;
use App\Rules\Exists;
use Illuminate\Support\Str;

class EventRegisStep extends MultiStepForm
{
    protected static string $startRoute = 'events.registrations.consent.create';
    protected static string $endRoute = 'events.registrations.end';
    protected static string $endView = 'event-registration.end';
    protected static string $sessionInputName = 'eventRegistInputs';
    protected static string $formTitle = 'Registration Form';
    protected static Event $event;

    protected static function setViewsData(): void
    {
        $event = request()->route('event');
        static::$viewsData = [
            'programs' => Course::all(),
            'yearLevels' => $event->participants,
            'formTitle' => static::$formTitle 
        ];
    }

    public static function store(Event $event): void
    {
        static::$event = $event;
        $inputs = session(static::$sessionInputName, []);
        $studentInput = $inputs['events.registrations.identity'];
        $student = new EventStudent();
        $student->student_id = $studentInput['student_id'];
        $student->first_name = $studentInput['first_name'];
        $student->middle_name = $studentInput['middle_name'];
        $student->last_name = $studentInput['last_name'];
        $student->suffix_name = $studentInput['suffix_name'];
        $student->course()->associate(Course::find($studentInput['program']));
        $student->year = $studentInput['year_level'];
        $student->email = $studentInput['email'];
        $student->section = $studentInput['section'];
        $student->save();
        $regis = new EventRegistration();
        $regis->token = Str::ulid();
        $regis->event()->associate($event);
        $regis->student()->associate($student);
        $regis->save();
        session(['events.registrations.qr-code.show' => [
            'studentId' => $student->student_id,
            'tag' => $event->tag,
            'token' => $regis->token
        ]]);
        static::setEndViewData();
    }

    protected static function setEndViewData(): void
    {
        static::$endViewData = [
            'formTitle' => static::$formTitle,
            'qrCodeRoute' => route('events.registrations.qr-code.show', [
                'event' => self::$event->public_id
            ])
        ];
    }

    public static function setRoutes(): void
    {
        $event = request()->route('event');
        self::$routes = [
            'events.registrations.consent' => [
                'view' => 'event-registration.consent',
                'rules' => [
                    'consent' => ['required', 'accepted']
                ]
            ],
            'events.registrations.identity' => [
                'view' => 'event-registration.identity',
                'rules' => [
                    'email' => ['required', 'email', 'max:255'],
                    'first_name' => ['required', 'max:50'],
                    'middle_name' => ['max:50'],
                    'last_name' => ['required', 'max:50'],
                    'suffix_name' => ['max:10'],
                    'student_id' => ['required', 'max:20', 
                        'regex:/^([A-Z0-9]+)-([A-Z0-9]+)-([A-Z0-9]+)-([A-Z0-9]+)$/'],
                    'program' => ['required', 'integer', 'exists:courses,id'],
                    'year_level' => ['required', 'integer', 
                        new Exists($event->participants()
                            ->getQuery(), 'id', [])],
                    'section' => ['required', 'exists:student_sections,section']
                ]
            ]
        ];
    }
}
