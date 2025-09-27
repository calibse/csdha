<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QrCode;
use Illuminate\Support\Str;
use App\Models\Event;
use App\Models\Student;
use App\Models\EventStudent;
use App\Models\EventRegistration;
use App\Models\Course;
use App\Http\Requests\StoreEventRegistrationRequest;
use App\Http\Requests\StoreConsentRequest;
use App\Http\Requests\StoreEventRegisIdentityRequest;
use App\Services\Format;

class EventRegistrationController extends Controller
{
    private static string $sessionDataName;

    public function __construct()
    {
        self::$sessionDataName = 'event_registration';
    }

    public function editConsentStep(Request $request, Event $event)
    {
        $inputs = session(self::$sessionDataName, []);
        $step = 0;
        return view('event-registration.consent', [
            'step' => $step,
            'completeSteps' => count($inputs),
            'submitRoute' => route('events.registrations.consent.store', [
                'event' => $event->public_id
            ]),
            'inputs' => $inputs[Format::getResourceRoute($request)] ?? []
        ] + self::multiFormData($event, $step));
    }

    public function storeConsentStep(StoreConsentRequest $request,
            Event $event)
    {
        self::storeFormStep(Format::getResourceRoute($request), $request);
        return redirect()->route('events.registrations.identity.edit', [
            'event' => $event->public_id
        ])->withFragment('content');
    }

    public function editIdentityStep(Request $request, Event $event)
    {
        $inputs = session(self::$sessionDataName, []);
        $step = 1;
        return view('event-registration.identity', [
            'step' => $step,
            'completeSteps' => count($inputs),
            'programs' => Course::all(),
            'yearLevels' => $event->participants,
            'previousStepRoute' => route('events.registrations.consent.edit', [
                'event' => $event->public_id
            ]) . '#content',
            'submitRoute' => route('events.registrations.identity.store', [
                'event' => $event->public_id
            ]),
            'lastStep' => true,
            'inputs' => $inputs[Format::getResourceRoute($request)] ?? []
        ] + self::multiFormData($event, $step));
    }

    public function storeIdentityStep(StoreEventRegisIdentityRequest $request,
            Event $event)
    {
        self::storeFormStep(Format::getResourceRoute($request), $request);
        return redirect()->route('events.registrations.end.show', [
            'event' => $event->public_id
        ])->withFragment('content');
    }

    public function showEndStep(Request $request, Event $event)
    {
        self::store($event);
        session()->forget(self::$sessionDataName);
        $step = 2;
        return view('event-registration.end', [
            'step' => $step,
            'completeSteps' => count($inputs),
            'qrCodeRoute' => route('events.registrations.qr-code.show', [
                'event' => $event->public_id
            ]),
            'end' => true
        ] + self::multiFormData($event, $step));
    }

    public function showQrCode(Request $request, Event $event)
    {
        $currentRoute = $request->route()->getName();
        $data = session('event_registration_qr_code', null);
        session()->forget('event_registration_qr_code');
        if (!$data) abort(404);
        $qrCode = new QrCode($data['token'], $data['tag'], $data['studentId']);
        return $qrCode->stream();
    }

    private static function store(Event $event): void
    {
        $inputs = session(self::$sessionDataName, []);
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
        session(['event_registration_qr_code' => [
            'studentId' => $student->student_id,
            'tag' => $event->tag,
            'token' => $regis->token
        ]]);
    }

    private static function multiFormData(Event $event, int $step): array
    {
        $routes = [
            route('events.registrations.consent.edit', [
                'event' => $event->public_id
            ]) . '#content',
            route('events.registrations.identity.edit', [
                'event' => $event->public_id
            ]) . '#content',
            route('events.registrations.end.show', [
                'event' => $event->public_id
            ]) . '#content',
        ];
        return [
            'formTitle' => 'Registration',
            'eventName' => $event->gpoaActivity->name,
            'event' => $event,
            'routes' => $routes
        ];
    }

    private static function storeFormStep(string $stepName,
            Request $request): void
    {
        $inputs = session(self::$sessionDataName, []);
        $inputs[$stepName] = $request->all();
        session([self::$sessionDataName => $inputs]);
    }
}
